<?php

namespace App\DataAccess\Remote;

use App\DataAccess\GitHubConnectionDataAccess;
use App\Models\GitHubConnection as GitHubConnectionModel;

class GitHubConnection extends BaseRemoteAccess implements GitHubConnectionDataAccess
{
    protected string $model = GitHubConnectionModel::class;
}
