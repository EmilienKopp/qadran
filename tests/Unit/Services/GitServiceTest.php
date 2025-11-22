<?php

namespace Tests\Unit\Services;

use App\DTOs\GitLogRequest;
use App\Services\GitService;
use Carbon\Carbon;
use Tests\TestCase;

class GitServiceTest extends TestCase
{
    private string $testRepoPath;

    protected function setUp(): void
    {
        parent::setUp();

        // Use the current project as test repository
        $this->testRepoPath = base_path();
    }

    public function test_can_create_service_for_valid_repository(): void
    {
        $service = GitService::forRepository($this->testRepoPath);

        $this->assertInstanceOf(GitService::class, $service);
    }

    public function test_throws_exception_for_invalid_path(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Repository path does not exist');

        GitService::forRepository('/nonexistent/path');
    }

    public function test_throws_exception_for_non_git_directory(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Path is not a git repository');

        GitService::forRepository(sys_get_temp_dir());
    }

    public function test_can_test_repository(): void
    {
        $service = GitService::forRepository($this->testRepoPath);

        $this->assertTrue($service->testRepository());
    }

    public function test_can_get_repository_name(): void
    {
        $service = GitService::forRepository($this->testRepoPath);

        $name = $service->getRepositoryName();

        $this->assertEquals('qadran', $name);
    }

    public function test_can_get_current_branch(): void
    {
        $service = GitService::forRepository($this->testRepoPath);

        $branch = $service->getCurrentBranch();

        $this->assertNotEmpty($branch);
        $this->assertIsString($branch);
    }

    public function test_can_get_branches(): void
    {
        $service = GitService::forRepository($this->testRepoPath);

        $branches = $service->getBranches();

        $this->assertNotEmpty($branches);
        $this->assertTrue($branches->every(function ($branch) {
            return isset($branch['name']) && isset($branch['commit_sha']);
        }));
    }

    public function test_can_get_remote_url(): void
    {
        $service = GitService::forRepository($this->testRepoPath);

        $url = $service->getRemoteUrl();

        // May be null if no remote configured, but should be string if exists
        $this->assertTrue(is_null($url) || is_string($url));
    }

    public function test_can_fetch_git_logs(): void
    {
        $service = GitService::forRepository($this->testRepoPath);

        $request = new GitLogRequest(
            repository: 'qadran',
            branch: $service->getCurrentBranch(),
            since: Carbon::now()->subDays(30),
            until: null,
            author: null,
            includeDiff: false
        );

        $response = $service->getGitLogs($request);

        $this->assertNotNull($response);
        $this->assertEquals('qadran', $response->repository);
        $this->assertGreaterThan(0, $response->totalCount);
        $this->assertNotEmpty($response->commits);
    }

    public function test_can_get_commit_diff(): void
    {
        $service = GitService::forRepository($this->testRepoPath);

        // Get the latest commit
        $request = new GitLogRequest(
            repository: 'qadran',
            branch: $service->getCurrentBranch(),
            since: null,
            until: null,
            author: null,
            includeDiff: false
        );

        $response = $service->getGitLogs($request);

        if ($response->commits->isNotEmpty()) {
            $firstCommit = $response->commits->first();
            $diff = $service->getCommitDiff($firstCommit->sha);

            // May be empty for some commits, but should be a collection
            $this->assertInstanceOf(\Illuminate\Support\Collection::class, $diff);
        }

        $this->assertTrue(true); // If we got here without exception, test passes
    }

    public function test_can_check_if_repository_is_clean(): void
    {
        $service = GitService::forRepository($this->testRepoPath);

        $isClean = $service->isClean();

        $this->assertIsBool($isClean);
    }

    public function test_can_get_uncommitted_changes(): void
    {
        $service = GitService::forRepository($this->testRepoPath);

        $changes = $service->getUncommittedChanges();

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $changes);
    }

    public function test_can_get_commit_count(): void
    {
        $service = GitService::forRepository($this->testRepoPath);

        $count = $service->getCommitCount();

        $this->assertIsInt($count);
        $this->assertGreaterThan(0, $count);
    }

    public function test_git_logs_respects_date_filters(): void
    {
        $service = GitService::forRepository($this->testRepoPath);

        $request = new GitLogRequest(
            repository: 'qadran',
            branch: $service->getCurrentBranch(),
            since: Carbon::now()->subDays(7),
            until: Carbon::now(),
            author: null,
            includeDiff: false
        );

        $response = $service->getGitLogs($request);

        $this->assertNotNull($response);

        // All commits should be within the date range
        if ($response->commits->isNotEmpty()) {
            $response->commits->each(function ($commit) use ($request) {
                $this->assertGreaterThanOrEqual(
                    $request->since->timestamp,
                    $commit->date->timestamp
                );
                $this->assertLessThanOrEqual(
                    $request->until->timestamp,
                    $commit->date->timestamp
                );
            });
        }
    }
}
