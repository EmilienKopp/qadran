<?php

namespace App\DataAccess\Remote;

use App\DataAccess\TenantDataAccess;

class Tenant extends BaseRemoteAccess implements TenantDataAccess
{
  public function getByDomainOrHost(string $identifier)
  {
    $response = $this->client->get("{$this->resourceEndpoint}/getByDomainOrHost", [
      'query' => [
        'identifier' => $identifier,
      ],
    ]);
    return json_decode($response->getBody()->getContents(), true);
  }
}