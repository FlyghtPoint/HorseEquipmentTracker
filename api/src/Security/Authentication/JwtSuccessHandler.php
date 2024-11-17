<?php

namespace App\Security\Authentication;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler as BaseSuccessHandler;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Psr\Log\LoggerInterface;

class JwtSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        private BaseSuccessHandler $baseHandler,
        private LoggerInterface $logger
    ) {}

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): JWTAuthenticationSuccessResponse
    {
        $user = $token->getUser();
        $this->logger->debug('JWT Authentication Success', [
            'user_id' => $user instanceof UserInterface ? $user->getUserIdentifier() : 'unknown',
            'roles' => $user instanceof UserInterface ? $user->getRoles() : []
        ]);

        $response = $this->baseHandler->onAuthenticationSuccess($request, $token);
        
        $this->logger->debug('JWT Response', [
            'response_content' => $response->getContent(),
            'response_status' => $response->getStatusCode()
        ]);

        return $response;
    }
}