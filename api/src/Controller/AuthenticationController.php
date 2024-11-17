<?php
// src/Controller/AuthenticationController.php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationController extends AbstractController
{
    public function getToken(UserInterface $user, JWTTokenManagerInterface $jwtManager)
    {
        $token = $jwtManager->create($user);
        return new JsonResponse(['token' => $token]);
    }
}
