<?php

namespace App\Services;

use App\DTOs\CommitData;
use App\DTOs\FileDiff;
use App\DTOs\GitLogRequest;
use App\DTOs\GitLogResponse;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Process;

/**
 * Local Git Service for Desktop context
 *
 * This service provides git operations using local git commands,
 * designed for desktop/native application usage.
 */
class GitService
{
    public function __construct(
        private string $repositoryPath
    ) {}

    /**
     * Create a new instance for a specific repository path
     */
    public static function forRepository(string $path): self
    {
        if (! is_dir($path)) {
            throw new \InvalidArgumentException("Repository path does not exist: {$path}");
        }

        if (! is_dir($path.'/.git')) {
            throw new \InvalidArgumentException("Path is not a git repository: {$path}");
        }

        return new self($path);
    }

    /**
     * Get git logs for a repository/branch with optional diff data
     */
    public function getGitLogs(GitLogRequest $request): GitLogResponse
    {
        $commits = $this->fetchCommits($request);

        return new GitLogResponse(
            repository: basename($this->repositoryPath),
            branch: $request->branch,
            since: $request->since,
            until: $request->until,
            commits: $commits,
            totalCount: $commits->count()
        );
    }

    /**
     * Test if repository is valid and accessible
     */
    public function testRepository(): bool
    {
        try {
            $result = $this->runGitCommand(['status']);

            return $result->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get current repository name
     */
    public function getRepositoryName(): string
    {
        return basename($this->repositoryPath);
    }

    /**
     * Get all branches in the repository
     */
    public function getBranches(): Collection
    {
        $result = $this->runGitCommand(['branch', '-a', '--format=%(refname:short)|%(objectname)']);

        if (! $result->successful()) {
            throw new \Exception('Failed to fetch branches: '.$result->errorOutput());
        }

        return collect(explode("\n", trim($result->output())))
            ->filter()
            ->map(function ($line) {
                [$name, $sha] = explode('|', $line);

                return [
                    'name' => $name,
                    'commit_sha' => $sha,
                ];
            });
    }

    /**
     * Get current branch name
     */
    public function getCurrentBranch(): string
    {
        $result = $this->runGitCommand(['rev-parse', '--abbrev-ref', 'HEAD']);

        if (! $result->successful()) {
            throw new \Exception('Failed to get current branch: '.$result->errorOutput());
        }

        return trim($result->output());
    }

    /**
     * Get repository remote URL
     */
    public function getRemoteUrl(?string $remote = 'origin'): ?string
    {
        $result = $this->runGitCommand(['remote', 'get-url', $remote]);

        if (! $result->successful()) {
            return null;
        }

        return trim($result->output());
    }

    /**
     * Fetch commits from git log
     */
    private function fetchCommits(GitLogRequest $request): Collection
    {
        $args = [
            'log',
            '--format=%H|%s|%an|%ae|%aI|%b',
            '--no-merges',
        ];

        if ($request->branch) {
            $args[] = $request->branch;
        }

        if ($request->since) {
            $args[] = '--since='.$request->since->toISOString();
        }

        if ($request->until) {
            $args[] = '--until='.$request->until->toISOString();
        }

        if ($request->author) {
            $args[] = '--author='.$request->author;
        }

        // Limit to reasonable number of commits
        $args[] = '--max-count=1000';

        $result = $this->runGitCommand($args);

        if (! $result->successful()) {
            throw new \Exception('Failed to fetch commits: '.$result->errorOutput());
        }

        $output = trim($result->output());
        if (empty($output)) {
            return collect();
        }

        return collect(explode("\n", $output))
            ->filter()
            ->map(function ($line) {
                $parts = explode('|', $line, 6);

                if (count($parts) < 5) {
                    return null;
                }

                [$sha, $subject, $author, $email, $date] = $parts;
                $body = $parts[5] ?? '';

                $message = trim($subject);
                if (! empty($body)) {
                    $message .= "\n\n".trim($body);
                }

                return new CommitData(
                    sha: $sha,
                    message: $message,
                    author: $author,
                    authorEmail: $email,
                    date: Carbon::parse($date),
                    url: null // Local git doesn't have URLs
                );
            })
            ->filter();
    }

    /**
     * Get diff for a specific commit
     */
    public function getCommitDiff(string $sha): Collection
    {
        $result = $this->runGitCommand([
            'show',
            '--format=',
            '--numstat',
            $sha,
        ]);

        if (! $result->successful()) {
            throw new \Exception("Failed to fetch commit diff for {$sha}: ".$result->errorOutput());
        }

        $output = trim($result->output());
        if (empty($output)) {
            return collect();
        }

        return collect(explode("\n", $output))
            ->filter()
            ->map(function ($line) use ($sha) {
                $parts = preg_split('/\s+/', $line, 3);

                if (count($parts) < 3) {
                    return null;
                }

                [$additions, $deletions, $filename] = $parts;

                // Handle binary files
                if ($additions === '-' && $deletions === '-') {
                    $additions = 0;
                    $deletions = 0;
                    $status = 'modified';
                } else {
                    $additions = (int) $additions;
                    $deletions = (int) $deletions;
                    $status = $this->determineFileStatus($filename, $sha);
                }

                return new FileDiff(
                    filename: $filename,
                    status: $status,
                    additions: $additions,
                    deletions: $deletions,
                    changes: $additions + $deletions,
                    patch: null, // Can be fetched separately if needed
                    blobUrl: null // Local git doesn't have URLs
                );
            })
            ->filter();
    }

    /**
     * Get patch for a specific file in a commit
     */
    public function getFilePatch(string $sha, string $filename): ?string
    {
        $result = $this->runGitCommand([
            'show',
            "{$sha}:{$filename}",
        ]);

        if (! $result->successful()) {
            return null;
        }

        return $result->output();
    }

    /**
     * Determine file status (added, modified, deleted, renamed)
     */
    private function determineFileStatus(string $filename, string $sha): string
    {
        $result = $this->runGitCommand([
            'diff-tree',
            '--no-commit-id',
            '--name-status',
            '-r',
            $sha,
        ]);

        if (! $result->successful()) {
            return 'modified';
        }

        $lines = collect(explode("\n", trim($result->output())))
            ->filter()
            ->map(function ($line) {
                return preg_split('/\s+/', $line, 2);
            });

        foreach ($lines as $parts) {
            if (count($parts) === 2 && $parts[1] === $filename) {
                $status = $parts[0];

                return match ($status[0]) {
                    'A' => 'added',
                    'M' => 'modified',
                    'D' => 'deleted',
                    'R' => 'renamed',
                    'C' => 'copied',
                    default => 'modified',
                };
            }
        }

        return 'modified';
    }

    /**
     * Get commit count between two refs
     */
    public function getCommitCount(?string $from = null, ?string $to = 'HEAD'): int
    {
        $args = ['rev-list', '--count'];

        if ($from) {
            $args[] = "{$from}..{$to}";
        } else {
            $args[] = $to;
        }

        $result = $this->runGitCommand($args);

        if (! $result->successful()) {
            throw new \Exception('Failed to get commit count: '.$result->errorOutput());
        }

        return (int) trim($result->output());
    }

    /**
     * Check if working directory is clean
     */
    public function isClean(): bool
    {
        $result = $this->runGitCommand(['status', '--porcelain']);

        if (! $result->successful()) {
            throw new \Exception('Failed to check repository status: '.$result->errorOutput());
        }

        return empty(trim($result->output()));
    }

    /**
     * Get list of uncommitted changes
     */
    public function getUncommittedChanges(): Collection
    {
        $result = $this->runGitCommand(['status', '--porcelain']);

        if (! $result->successful()) {
            throw new \Exception('Failed to get uncommitted changes: '.$result->errorOutput());
        }

        $output = trim($result->output());
        if (empty($output)) {
            return collect();
        }

        return collect(explode("\n", $output))
            ->filter()
            ->map(function ($line) {
                $status = substr($line, 0, 2);
                $filename = trim(substr($line, 3));

                return [
                    'status' => trim($status),
                    'filename' => $filename,
                ];
            });
    }

    /**
     * Run a git command in the repository directory
     */
    private function runGitCommand(array $args): \Illuminate\Process\ProcessResult
    {
        $command = array_merge(['git', '-C', $this->repositoryPath], $args);

        $result = Process::run($command);

        return $result;
    }
}
