<?php

namespace App\Exceptions;

class GitHubServiceException extends \Exception
{
    public static function connectionFailed(): self
    {
        return new self('GitHub connection failed');
    }

    public static function tokenExpired(): self
    {
        return new self('GitHub access token has expired');
    }

    public static function repositoryNotFound(string $repository): self
    {
        return new self("Repository '{$repository}' not found or not accessible");
    }

    public static function branchNotFound(string $branch): self
    {
        return new self("Branch '{$branch}' not found");
    }

    public static function notConnected(): self
    {
        return new self('GitHub account is not connected');
    }
}
