<?php

namespace App\Security\AuthenticationEntryPoint;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class LoginFormAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator
    ) {}

    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
    {
        // Stocke l'URL initialement demandée dans la session
        $request->getSession()->set('_security.main.target_path', $request->getUri());

        // Redirige vers la page de login
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }
}