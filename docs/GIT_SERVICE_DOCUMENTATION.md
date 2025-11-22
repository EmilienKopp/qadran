# GitService Documentation

## Overview

`GitService` is a local git operations service designed for desktop/native application contexts. It provides git functionality using local git commands, similar to what `GitHubService` does but without requiring API access or authentication.

## Key Features

- 🔍 **Repository Operations**: Access repository information, branches, and remote URLs
- 📊 **Git Log Retrieval**: Fetch commit history with flexible filtering options
- 📝 **Diff Analysis**: Get detailed diffs for specific commits
- ✅ **Status Checking**: Check repository cleanliness and uncommitted changes
- 🎯 **Local-First**: No API keys, tokens, or network access required

## Basic Usage

### Creating a Service Instance

```php
use App\Services\GitService;

// Create service for a specific repository path
$service = GitService::forRepository('/path/to/your/repo');
```

### Getting Repository Information

```php
// Get repository name
$name = $service->getRepositoryName();

// Get current branch
$branch = $service->getCurrentBranch();

// Get all branches
$branches = $service->getBranches();
// Returns: Collection of ['name' => '...', 'commit_sha' => '...']

// Get remote URL (if configured)
$url = $service->getRemoteUrl(); // Default: 'origin'
$url = $service->getRemoteUrl('upstream'); // Custom remote
```

### Fetching Git Logs

```php
use App\DTOs\GitLogRequest;
use Carbon\Carbon;

// Create a request with filters
$request = new GitLogRequest(
    repository: 'my-project',
    branch: 'main',
    since: Carbon::now()->subDays(30),
    until: Carbon::now(),
    author: 'john@example.com', // Optional
    includeDiff: false
);

// Get git logs
$response = $service->getGitLogs($request);

// Access commit data
foreach ($response->commits as $commit) {
    echo "SHA: {$commit->sha}\n";
    echo "Author: {$commit->author} ({$commit->authorEmail})\n";
    echo "Date: {$commit->date->format('Y-m-d H:i:s')}\n";
    echo "Message: {$commit->message}\n\n";
}
```

### Getting Commit Diffs

```php
// Get diff for a specific commit
$diff = $service->getCommitDiff('abc123def456');

foreach ($diff as $file) {
    echo "File: {$file->filename}\n";
    echo "Status: {$file->status}\n"; // added, modified, deleted, renamed
    echo "Changes: +{$file->additions} -{$file->deletions}\n\n";
}
```

### Checking Repository Status

```php
// Check if working directory is clean
if ($service->isClean()) {
    echo "No uncommitted changes\n";
} else {
    echo "There are uncommitted changes\n";
}

// Get list of uncommitted changes
$changes = $service->getUncommittedChanges();
foreach ($changes as $change) {
    echo "{$change['status']} {$change['filename']}\n";
}
```

### Additional Utilities

```php
// Test if repository is valid
$isValid = $service->testRepository();

// Get commit count
$totalCommits = $service->getCommitCount();
$commitsSince = $service->getCommitCount('v1.0.0', 'HEAD');

// Get file patch for specific commit
$patch = $service->getFilePatch('abc123', 'path/to/file.php');
```

## Use Cases

### Desktop Application Context

This service is specifically designed for desktop/native applications where:
- Direct filesystem access to git repositories is available
- No network connectivity or API authentication is required
- Local git commands can be executed
- Real-time repository monitoring is needed

### Example: Daily Log Generator

```php
use App\Services\GitService;
use App\DTOs\GitLogRequest;
use Carbon\Carbon;

$service = GitService::forRepository('/home/user/projects/my-app');

$request = new GitLogRequest(
    repository: $service->getRepositoryName(),
    branch: $service->getCurrentBranch(),
    since: Carbon::today(),
    until: null,
    author: null,
    includeDiff: false
);

$todaysWork = $service->getGitLogs($request);

echo "Today's commits on {$request->branch}:\n";
foreach ($todaysWork->commits as $commit) {
    echo "- {$commit->message} by {$commit->author}\n";
}
```

## Comparison with GitHubService

| Feature | GitService | GitHubService |
|---------|-----------|---------------|
| Authentication | None required | GitHub token required |
| Network | Local only | Requires API access |
| Rate Limits | None | GitHub API limits |
| URLs | Not available | Commit URLs included |
| Context | Desktop/Native | Web/Cloud |
| Speed | Fast (local) | Depends on network |

## Error Handling

The service throws exceptions for various error conditions:

```php
try {
    $service = GitService::forRepository('/invalid/path');
} catch (\InvalidArgumentException $e) {
    // Handle invalid repository path
}

try {
    $commits = $service->getGitLogs($request);
} catch (\Exception $e) {
    // Handle git command failures
}
```

## Testing

The service includes comprehensive unit tests:

```bash
php artisan test --filter=GitServiceTest
```

## Requirements

- Git must be installed and available in the system PATH
- Repository must be a valid git repository with `.git` directory
- Appropriate filesystem permissions to read repository files

## Notes

- The service limits git log queries to 1000 commits by default
- Binary files in diffs will have 0 additions/deletions
- Commit URLs are always `null` since local git doesn't have web URLs
- The service uses Laravel's `Process` facade for executing git commands
