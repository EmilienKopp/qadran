<?php

namespace App\Services;

use App\Models\GitHubConnection;
use App\Models\RepositorySettings;
use App\DTOs\GitLogRequest;
use App\DTOs\GitLogResponse;
use App\DTOs\CommitData;
use App\DTOs\FileDiff;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GitHubService
{
    private const GITHUB_API_BASE = 'https://api.github.com';
    private const CACHE_TTL = 300; // 5 minutes

    public function __construct(
        private GitHubConnection $connection
    ) {}

    /**
     * Create a new instance for a specific user's GitHub connection
     */
    public static function forUser(int $userId): self
    {
        $connection = GitHubConnection::where('user_id', $userId)->firstOrFail();
        return new self($connection);
    }

    /**
     * Get git logs for a repository/branch with optional diff data
     */
    public function getGitLogs(GitLogRequest $request): GitLogResponse
    {
        // $settings = $this->getRepositorySettings($request->repository, $request->branch);
        
        $commits = $this->fetchCommits($request);
        
        // if ($request->includeDiff) {
        //     $commits = $this->enrichCommitsWithDiffs($commits, $request, $settings);
        // }

        return new GitLogResponse(
            repository: $request->repository,
            branch: $request->branch,
            since: $request->since,
            until: $request->until,
            commits: $commits,
            totalCount: $commits->count()
        );
    }

    /**
     * Save repository-specific settings
     */
    public function saveRepositorySettings(
        string $repository,
        string $branch,
        array $excludedFolders = [],
        array $excludedExtensions = [],
        bool $includeDiffByDefault = true
    ): RepositorySettings {
        return RepositorySettings::updateOrCreate(
            [
                'github_connection_id' => $this->connection->id,
                'repository' => $repository,
                'branch' => $branch,
            ],
            [
                'excluded_folders' => $excludedFolders,
                'excluded_extensions' => $excludedExtensions,
                'include_diff_by_default' => $includeDiffByDefault,
            ]
        );
    }

    /**
     * Get repository settings
     */
    public function getRepositorySettings(string $repository, string $branch): ?RepositorySettings
    {
        return RepositorySettings::where([
            'github_connection_id' => $this->connection->id,
            'repository' => $repository,
            'branch' => $branch,
        ])->first();
    }

    /**
     * Test GitHub connection
     */
    public function testConnection(): bool
    {
        try {
            $response = $this->makeGitHubRequest('/user');
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get user's accessible repositories
     */
    public function getRepositories(): Collection
    {
        $cacheKey = "github_repos_{$this->connection->id}";
        $owner = $this->connection->username;
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use($owner) {
            $response = $this->makeGitHubRequest("/users/{$owner}/repos", [
                'sort' => 'updated',
                'per_page' => 100,
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to fetch repositories');
            }

            return collect($response->json())->map(function ($repo) {
                return [
                    'name' => $repo['name'],
                    'full_name' => $repo['full_name'],
                    'private' => $repo['private'],
                    'default_branch' => $repo['default_branch'],
                    'updated_at' => $repo['updated_at'],
                ];
            });
        });
    }

    /**
     * Get branches for a repository
     */
    public function getBranches(string $repository): Collection
    {
        $cacheKey = "github_branches_{$this->connection->id}_{$repository}";
        $owner = $this->connection->username;
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($owner,$repository) {
            $response = $this->makeGitHubRequest("/repos/{$owner}/{$repository}/branches");

            if (!$response->successful()) {
                \Log::error('Failed to fetch branches', [
                    'repository' => $repository,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
                throw new \Exception('Failed to fetch branches');
            }

            return collect($response->json())->map(function ($branch) {
                return [
                    'name' => $branch['name'],
                    'commit_sha' => $branch['commit']['sha'],
                ];
            });
        });
    }

    /**
     * Fetch commits from GitHub API
     */
    private function fetchCommits(GitLogRequest $request): Collection
    {
        $params = [
            'sha' => $request->branch,
            'since' => $request->since ? $request->since->toISOString() : null,
            'until' => $request->until ? $request->until->toISOString() : null,
            'per_page' => 100,
        ];

        if ($request->author) {
            $params['author'] = $request->author;
        }
        $owner = $this->connection->username;

        $allCommits = collect();
        $page = 1;

        do {
            $params['page'] = $page;
            $response = $this->makeGitHubRequest("/repos/{$owner}/{$request->repository}/commits", $params);

            if (!$response->successful()) {
                throw new \Exception('Failed to fetch commits');
            }

            $commits = collect($response->json());
            $allCommits = $allCommits->merge($commits);
            
            $page++;
        } while ($commits->count() === 100 && $page <= 10); // Limit to 1000 commits max

        return $allCommits->map(function ($commit) {
            return new CommitData(
                sha: $commit['sha'],
                message: $commit['commit']['message'],
                author: $commit['commit']['author']['name'],
                authorEmail: $commit['commit']['author']['email'],
                date: Carbon::parse($commit['commit']['author']['date']),
                url: $commit['html_url']
            );
        });
    }

    /**
     * Enrich commits with diff data
     */
    private function enrichCommitsWithDiffs(
        Collection $commits, 
        GitLogRequest $request, 
        ?RepositorySettings $settings
    ): Collection {
        return $commits->map(function (CommitData $commit) use ($request, $settings) {
            $diff = $this->getCommitDiff($request->repository, $commit->sha, $settings);
            $commit->setDiff($diff);
            return $commit;
        });
    }

    /**
     * Get diff for a specific commit
     */
    private function getCommitDiff(string $repository, string $sha, ?RepositorySettings $settings): Collection
    {
        $response = $this->makeGitHubRequest("/repos/{$repository}/commits/{$sha}");

        if (!$response->successful()) {
            throw new \Exception("Failed to fetch commit diff for {$sha}");
        }

        $commitData = $response->json();
        $files = collect($commitData['files'] ?? []);

        // Apply filtering based on settings
        if ($settings) {
            $files = $this->filterFiles($files, $settings);
        }

        return $files->map(function ($file) {
            return new FileDiff(
                filename: $file['filename'],
                status: $file['status'],
                additions: $file['additions'],
                deletions: $file['deletions'],
                changes: $file['changes'],
                patch: $file['patch'] ?? null,
                blobUrl: $file['blob_url'] ?? null
            );
        });
    }

    /**
     * Filter files based on repository settings
     */
    private function filterFiles(Collection $files, RepositorySettings $settings): Collection
    {
        return $files->filter(function ($file) use ($settings) {
            $filename = $file['filename'];
            
            // Check excluded folders
            foreach ($settings->excluded_folders as $folder) {
                if (str_starts_with($filename, $folder . '/')) {
                    return false;
                }
            }

            // Check excluded extensions
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            if (in_array($extension, $settings->excluded_extensions)) {
                return false;
            }

            return true;
        });
    }

    /**
     * Make authenticated request to GitHub API
     */
    private function makeGitHubRequest(string $endpoint, array $params = [])
    {
        return Http::withHeaders([
            'Authorization' => 'token ' . $this->connection->access_token,
            'Accept' => 'application/vnd.github.v3+json',
            'User-Agent' => config('app.name', 'Laravel'),
        ])->get(self::GITHUB_API_BASE . $endpoint, $params);
    }
}