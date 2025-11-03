<?php

namespace App\DataAccess\Local;

use App\DataAccess\GitHubConnectionDataAccess;
use App\Models\GitHubConnection as GitHubConnectionModel;

class GitHubConnection extends BaseLocalAccess implements GitHubConnectionDataAccess
{
    protected string $model = GitHubConnectionModel::class;
}
