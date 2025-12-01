<?php

namespace App\DataAccess\Remote;

use App\Contracts\BaseDataAccess;
use GuzzleHttp\Client;

abstract class BaseRemoteAccess implements BaseDataAccess
{
    protected string $resourceEndpoint;

    protected string $model;

    public function __construct(protected Client $client, protected string $baseUrl)
    {
        $this->resourceEndpoint = $this->getEndpoint();
    }

    public function find($id)
    {
        $response = $this->client->get("{$this->resourceEndpoint}/find/{$id}");
        $object = json_decode($response->getBody()->getContents(), true);

        return $this->castToModel($object);
    }

    public function firstWhere($column, $operator, $value = null)
    {
        // Handle Laravel's 2-parameter shorthand: firstWhere($column, $value)
        // If value is null and operator is not null, assume operator is actually the value
        if ($value === null && $operator !== null) {
            $value = $operator;
            $operator = '=';
        }

        $response = $this->client->get("{$this->resourceEndpoint}/firstWhere", [
            'query' => [
                'column' => $column,
                'operator' => $operator,
                'value' => $value,
            ],
        ]);
        $object = json_decode($response->getBody()->getContents(), true);

        return $this->castToModel($object);
    }

    public function all()
    {
        $response = $this->client->get("{$this->resourceEndpoint}/all");
        $data = json_decode($response->getBody()->getContents(), true);

        return $this->castToCollection($data);
    }

    public function where($column, $operator, $value = null)
    {
        // Handle Laravel's 2-parameter shorthand: where($column, $value)
        if ($value === null && $operator !== null) {
            $value = $operator;
            $operator = '=';
        }

        $response = $this->client->get("{$this->resourceEndpoint}/where", [
            'query' => [
                'column' => $column,
                'operator' => $operator,
                'value' => $value,
            ],
        ]);
        $data = json_decode($response->getBody()->getContents(), true);

        return $this->castToCollection($data);
    }

    public function query()
    {
        // For remote access, we return a local query builder
        // This allows chaining but executes locally on cached/synced data
        return $this->model::query();
    }

    protected function getEndpoint()
    {
        return "{$this->baseUrl}/api/".class_basename($this);
    }

    private function castToModel($data)
    {
        if (! $data) {
            return null;
        }

        if (! isset($this->model)) {
            throw new \App\Exceptions\MissingModelCastException;
        }

        if (is_array($data) && count($data) === 1) {
            $firstKey = array_key_first($data);
            if (str_contains($firstKey, 'App\\Models\\')) {
                $data = $data[$firstKey];
            }
        }

        $model = new $this->model;
        $model->forceFill($data);
        $model->exists = true;

        return $model;
    }

    private function castToCollection($data)
    {
        if (! $data) {
            return collect([]);
        }

        if (! isset($this->model)) {
            throw new \App\Exceptions\MissingModelCastException;
        }

        // Handle wrapped response format
        if (is_array($data) && count($data) === 1) {
            $firstKey = array_key_first($data);
            if (str_contains($firstKey, 'App\\Models\\')) {
                $data = $data[$firstKey];
            }
        }

        $models = [];
        foreach ($data as $item) {
            $model = new $this->model;
            $model->forceFill($item);
            $model->exists = true;
            $models[] = $model;
        }

        return collect($models);
    }
}
