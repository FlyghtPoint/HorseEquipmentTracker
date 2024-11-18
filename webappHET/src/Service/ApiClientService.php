<?php
namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ApiClientService
{
    private $client;
    private $serializer;
    private $tokenStorage;
    private $apiBaseUrl;
    private $jwtToken;

    public function __construct(
        SerializerInterface $serializer, 
        TokenStorageInterface $tokenStorage,
        string $apiBaseUrl
    ) {
        $this->client = HttpClient::create([
            'verify_peer' => false,
            'verify_host' => false
            // 'local_cert' => true
        ]);
        $this->serializer = $serializer;
        $this->tokenStorage = $tokenStorage;
        $this->apiBaseUrl = $apiBaseUrl;
    }

    public function setJwtToken(string $token)
    {
        $this->jwtToken = $token;
    }

    private function getHeaders(): array
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];

        if ($this->jwtToken) {
            $headers['Authorization'] = 'Bearer ' . $this->jwtToken;
        }

        return $headers;
    }

    public function getCollection(string $resource, array $params = []): array
    {
        $response = $this->client->request(
            'GET', 
            $this->apiBaseUrl . $resource,
            [
                'headers' => $this->getHeaders(),
                'query' => $params
            ]
        );

        return $response->toArray();
    }

    public function getItem(string $resource, string $id): array
    {
        $response = $this->client->request(
            'GET', 
            $this->apiBaseUrl . $resource . '/' . $id,
            ['headers' => $this->getHeaders()]
        );

        return $response->toArray();
    }

    public function create(string $resource, array $data): array
    {
        $response = $this->client->request(
            'POST', 
            $this->apiBaseUrl . $resource,
            [
                'headers' => $this->getHeaders(),
                'json' => $data
            ]
        );

        return $response->toArray();
    }

    public function update(string $resource, string $id, array $data): array
    {
        $response = $this->client->request(
            'PUT', 
            $this->apiBaseUrl . $resource . '/' . $id,
            [
                'headers' => $this->getHeaders(),
                'json' => $data
            ]
        );

        return $response->toArray();
    }

    public function delete(string $resource, string $id): bool
    {
        $response = $this->client->request(
            'DELETE', 
            $this->apiBaseUrl . $resource . '/' . $id,
            ['headers' => $this->getHeaders()]
        );

        return $response->getStatusCode() === 204;
    }
}
