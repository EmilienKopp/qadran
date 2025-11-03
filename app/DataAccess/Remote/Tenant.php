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

    $data = json_decode($response->getBody()->getContents(), true);
    $model = new \App\Models\Landlord\Tenant($data);
    $model->exists = true;
    return $model;
  }
}