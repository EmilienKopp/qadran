# Git Command Usage

## Overview

The `git:run` artisan command provides a convenient interface to run local git operations using the `GitService`. Perfect for testing, automation, and scheduled tasks.

## Basic Usage

```bash
php artisan git:run {action} [options]
```

## Available Actions

### 1. `info` - Repository Information

Get general information about the repository.

```bash
php artisan git:run info
```

**Output:**
```
Repository Information

Name:           qadran
Current Branch: lp
Remote URL:     git@github.com:EmilienKopp/qadran.git
Total Commits:  240
Status:         Clean
```

**With JSON:**
```bash
php artisan git:run info --json
```

### 2. `status` - Working Directory Status

Check if there are uncommitted changes.

```bash
php artisan git:run status
```

**Output:**
```
✗ Working directory has uncommitted changes

+--------+----------------------------------------+
| Status | File                                   |
+--------+----------------------------------------+
| M      | app/DTOs/CommitData.php               |
| ??     | app/Services/GitService.php           |
+--------+----------------------------------------+
```

### 3. `branches` - List All Branches

Show all branches with their commit SHAs.

```bash
php artisan git:run branches
```

**Output:**
```
Branches for qadran
Current: lp

+------------------+------------+
| Branch           | Commit SHA |
+------------------+------------+
| * lp             | aea5c87    |
|   main           | 9682fd8    |
|   origin/main    | 9682fd8    |
+------------------+------------+
```

### 4. `logs` - Git Commit History

Fetch commit logs with optional filters.

```bash
# Recent commits
php artisan git:run logs

# Last 7 days
php artisan git:run logs --since="7 days ago"

# Date range
php artisan git:run logs --since="2025-01-01" --until="2025-01-31"

# Specific author
php artisan git:run logs --author="john@example.com"

# Specific branch
php artisan git:run logs --branch=main

# JSON output
php artisan git:run logs --since="1 day ago" --json
```

**Output:**
```
Git logs for qadran on branch lp
Total commits: 2

aea5c87efe53263ea4a90316513315aef1826423
EmilienKopp <emilien.kopp@gmail.com>
2025-11-21 09:26:03
feat(MorphCard, Landing): implement feature toggle
```

### 5. `diff` - Commit Diff

Get file changes for a specific commit.

```bash
php artisan git:run diff --sha=aea5c87

# With JSON
php artisan git:run diff --sha=aea5c87 --json
```

**Output:**
```
Diff for commit aea5c87

+----------+--------------------------------------------------+---------+
| Status   | File                                             | Changes |
+----------+--------------------------------------------------+---------+
| modified | resources/js/Components/Display/MorphCard.svelte | +25 -7  |
| modified | resources/js/Pages/Landing.svelte                | +4 -31  |
+----------+--------------------------------------------------+---------+
```

## Global Options

### `--path`
Specify a different repository path (defaults to current Laravel project).

```bash
php artisan git:run info --path=/path/to/other/repo
```

### `--json`
Output results in JSON format (useful for automation and parsing).

```bash
php artisan git:run info --json
php artisan git:run logs --since="1 day ago" --json
```

## Date Format Examples

The `--since` and `--until` options accept various formats:

```bash
# Relative dates
--since="1 hour ago"
--since="7 days ago"
--since="2 weeks ago"
--since="3 months ago"

# Specific dates
--since="2025-01-01"
--since="January 1, 2025"
--since="2025-01-01 14:30:00"

# Keywords
--since="today"
--since="yesterday"
--until="now"
```

## Scheduling Examples

### Daily Commit Summary

Add to your scheduler in `routes/console.php`:

```php
use Illuminate\Support\Facades\Schedule;

// Daily summary of commits
Schedule::command('git:run logs --since="1 day ago" --json')
    ->daily()
    ->at('23:00');
```

### Weekly Repository Report

```php
// Weekly branch status check
Schedule::command('git:run branches --json')
    ->weekly()
    ->mondays()
    ->at('09:00');
```

### Automated Status Check

```php
// Check for uncommitted changes every hour
Schedule::command('git:run status --json')
    ->hourly()
    ->when(function () {
        return config('app.env') === 'local';
    });
```

## Testing Examples

### In Feature Tests

```php
use Illuminate\Support\Facades\Artisan;

public function test_can_get_repository_info(): void
{
    $exitCode = Artisan::call('git:run', ['action' => 'info', '--json' => true]);
    
    $this->assertEquals(0, $exitCode);
    
    $output = Artisan::output();
    $data = json_decode($output, true);
    
    $this->assertArrayHasKey('name', $data);
    $this->assertArrayHasKey('current_branch', $data);
}
```

### In CI/CD Pipeline

```bash
# Check if repository is clean before deployment
php artisan git:run status --json | jq -r '.is_clean'

# Get current branch name
php artisan git:run info --json | jq -r '.current_branch'

# Count commits since last tag
php artisan git:run logs --since="v1.0.0" --json | jq '.total_count'
```

## Integration with Other Tools

### Parse with jq

```bash
# Get list of changed files
php artisan git:run diff --sha=abc123 --json | jq -r '.files[].filename'

# Get author emails from last week
php artisan git:run logs --since="7 days ago" --json | jq -r '.commits[].email' | sort -u

# Count commits per day
php artisan git:run logs --since="30 days ago" --json | jq -r '.commits[].date' | cut -d'T' -f1 | sort | uniq -c
```

### Export to File

```bash
# Export commit history to JSON
php artisan git:run logs --since="1 month ago" --json > commits.json

# Export branch list
php artisan git:run branches --json > branches.json
```

## Error Handling

The command returns appropriate exit codes:

- `0` - Success
- `1` - Failure (invalid repository, unknown action, etc.)

```bash
# Check exit code in scripts
if php artisan git:run info --path=/invalid/path; then
    echo "Repository valid"
else
    echo "Repository invalid"
fi
```

## Tips

1. **Use JSON for automation** - Always use `--json` when integrating with scripts or CI/CD
2. **Filter by date** - Use `--since` and `--until` to narrow down log results
3. **Test locally first** - Run commands manually before scheduling them
4. **Check exit codes** - Always verify command success in automated workflows
5. **Combine with jq** - Parse JSON output easily with jq for complex data extraction
