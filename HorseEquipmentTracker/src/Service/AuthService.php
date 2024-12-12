<?php
namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Bundle\SecurityBundle\Security;

class AuthService
{
    private $client;
    private $apiBaseUrl;
    private $session;
    private $security;

    public function __construct(
        string $apiBaseUrl, 
        SessionInterface $session,
        Security $security
    ) {
        $this->client = HttpClient::create([
            'verify_peer' => false,
            'verify_host' => false
        ]);
        $this->apiBaseUrl = $apiBaseUrl;
        $this->session = $session;
        $this->security = $security;
    }

    public function login(string $email, string $password): ?string
    {
        try {
            // First, try to authenticate against the API
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
            
            if (!isset($data['token'])) {
                throw new AuthenticationException('Authentication failed');
            }

            // Store the JWT token
            $this->session->set('jwt_token', $data['token']);

            return $data['token'];
        } catch (\Exception $e) {
            throw new AuthenticationException('Invalid credentials');
        }
    }

    public function isAuthenticated(): bool
    {
        return $this->security->isGranted('IS_AUTHENTICATED_FULLY');
    }

    public function isAdmin(): bool
    {
        return $this->security->isGranted('ROLE_ADMIN');
    }

    public function getCurrentUser(): ?object
    {
        return $this->security->getUser();
    }

    public function getToken(): ?string
    {
        return $this->session->get('jwt_token');
    }

    public function logout(): void
    {
        $this->session->remove('jwt_token');
    }
}