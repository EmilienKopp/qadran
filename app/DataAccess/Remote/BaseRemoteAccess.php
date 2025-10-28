<?php

namespace App\DataAccess\Remote;

use App\Contracts\BaseDataAccess;
use GuzzleHttp\Client;

abstract class BaseRemoteAccess implements BaseDataAccess
{
  protected string $resourceEndpoint;
  
  public function __construct(protected Client $client, protected string $baseUrl)
  {
    $this->resourceEndpoint = $this->getEndpoint();
  }

  public function find($id)
  {
    $response = $this->client->get("{$this->resourceEndpoint}/find/{$id}");
    return json_decode($response->getBody()->getContents(), true);
  }

  public function firstWhere($column, $operator, $value = null)
  {
    
    $response = $this->client->get("{$this->resourceEndpoint}/firstWhere", [
      'query' => [
        'column' => $column,
        'operator' => $operator,
        'value' => $value,
      ],
    ]);
    return json_decode($response->getBody()->getContents(), true);
  }

  protected function getEndpoint()
  {
    return "{$this->baseUrl}/api/" . class_basename($this);
  }
}