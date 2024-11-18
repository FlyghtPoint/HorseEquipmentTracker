<?php
namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthService
{
    private $client;
    private $apiBaseUrl;
    private $session;

    public function __construct(
        string $apiBaseUrl, 
        SessionInterface $session
    ) {
        $this->client = HttpClient::create();
        $this->apiBaseUrl = $apiBaseUrl;
        $this->session = $session;
    }

    public function login(string $email, string $password): ?string
    {
        try {
            $response = $this->client->request(
                'POST', 
                $this->apiBaseUrl . '/login_check',
                [
                    'json' => [
                        'email' => $email,
                        'password' => $password
                    ]
                ]
            );

            $data = $response->toArray();
            
            // Store token in session
            $this->session->set('jwt_token', $data['token']);

            return $data['token'];
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getToken(): ?string
    {
        return $this->session->get('jwt_token');
    }

    public function logout()
    {
        $this->session->remove('jwt_token');
    }
}
