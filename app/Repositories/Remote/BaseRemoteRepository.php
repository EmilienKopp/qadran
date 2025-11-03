<?php

namespace App\Repositories\Remote;

use App\Repositories\BaseRepositoryInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;

abstract class BaseRemoteRepository implements BaseRepositoryInterface
{
    protected string $model;
    protected string $resourceEndpoint;

    public function __construct(protected Client $client, protected ?string $baseUrl = null)
    {
        $this->baseUrl ??= config('services.api.base_url');
        $this->resourceEndpoint = $this->getEndpoint();
    }

    protected function getEndpoint(): string
    {
        // Override this in child classes if needed
        $className = class_basename(static::class);
        // Remove "Remote" and "Repository" suffixes and convert to plural
        $resource = str_replace(['Remote', 'Repository'], '', $className);
        $resource = strtolower(\Illuminate\Support\Str::plural($resource));
        $endpoint = "{$this->baseUrl}/api/{$resource}";
        
        \Log::debug('BaseRemoteRepository getEndpoint', [
            'className' => $className,
            'resource' => $resource,
            'baseUrl' => $this->baseUrl,
            'endpoint' => $endpoint,
        ]);
        
        return $endpoint;
    }

    /**
     * Make a GET request to the remote API
     */
    protected function get(string $endpoint, array $params = []): array
    {
        $url = $endpoint;
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        \Log::debug('BaseRemoteRepository making GET request', [
            'url' => $url,
            'baseUrl' => $this->baseUrl,
            'endpoint' => $endpoint,
            'params' => $params,
        ]);

        $response = $this->client->get($url);
        
        if ($response->getStatusCode() !== 200) {
            \Log::error('BaseRemoteRepository GET request failed', [
                'url' => $url,
                'status_code' => $response->getStatusCode(),
                'response_body' => $response->getBody()->getContents(),
            ]);
            throw new \Exception("Remote API request failed: {$response->getStatusCode()}");
        }

        $data = json_decode($response->getBody()->getContents(), true);
        \Log::debug('BaseRemoteRepository GET response', ['data' => $data]);
        
        return $data;
    }

    /**
     * Make a POST request to the remote API
     */
    protected function post(string $endpoint, array $data = []): array
    {
        $response = $this->client->post($endpoint, [
            'json' => $data,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ]);

        if (!in_array($response->getStatusCode(), [200, 201])) {
            throw new \Exception("Remote API request failed: {$response->getStatusCode()}");
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Make a PUT request to the remote API
     */
    protected function put(string $endpoint, array $data = []): array
    {
        $response = $this->client->put($endpoint, [
            'json' => $data,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ]);

        if (!$response->getStatusCode() === 200) {
            throw new \Exception("Remote API request failed: {$response->getStatusCode()}");
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Make a DELETE request to the remote API
     */
    protected function deleteRequest(string $endpoint): bool
    {
        $response = $this->client->delete($endpoint);
        
        return in_array($response->getStatusCode(), [200, 204]);
    }

    protected function hydrate(?array $data)
    {
        if (!$data) {
            return null;
        }

        if (!isset($this->model)) {
            throw new \Exception('Model property must be set in repository');
        }

        // Handle wrapped response format
        if (is_array($data) && count($data) === 1) {
            $firstKey = array_key_first($data);
            if (str_contains($firstKey, 'App\\Models\\')) {
                $data = $data[$firstKey];
            }
        }

        $model = new $this->model();
        $model->forceFill($data);
        $model->exists = true;
        return $model;
    }

    protected function hydrateCollection(?array $data)
    {
        if (!$data) {
            return collect([]);
        }

        // Handle wrapped response format
        if (is_array($data) && count($data) === 1) {
            $firstKey = array_key_first($data);
            if (str_contains($firstKey, 'App\\Models\\')) {
                $data = $data[$firstKey];
            }
        }

        return collect($data)->map(fn($item) => $this->hydrate($item));
    }

    protected function getRepositoryName(): string
    {
        return str_replace('Remote', '', class_basename(static::class));
    }
}
