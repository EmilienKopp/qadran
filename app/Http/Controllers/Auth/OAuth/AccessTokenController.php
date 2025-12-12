<?php

namespace App\Http\Controllers\Auth\OAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AccessTokenController
{
    public function __construct(public \Laravel\Passport\Http\Controllers\AccessTokenController $passport)
    {}

    public function issueToken(Request $request)
    {
        return $this->passport->issueToken(
            app('oauth2-server.psr7factory')->createServerRequest($request),
            app('oauth2-server.psr7factory')->createResponse()
        );
    }
}
