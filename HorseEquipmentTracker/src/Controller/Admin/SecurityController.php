<?php
namespace App\Controller\Admin;

use App\Service\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/admin/login', name: 'admin_login')]
    public function login(
        Request $request, 
        AuthenticationUtils $authenticationUtils,
        AuthService $authService
    ): Response {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($request->isMethod('POST')) {
            $email = $request->request->get('_username');
            $password = $request->request->get('_password');

            $token = $authService->login($email, $password);

            if ($token) {
                return $this->redirectToRoute('admin_dashboard');
            }

            $error = new \stdClass();
            $error->messageKey = 'Invalid credentials';
        }

        return $this->render('admin/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/admin/logout', name: 'admin_logout')]
    public function logout(AuthService $authService)
    {
        $authService->logout();
        return $this->redirectToRoute('admin_login');
    }
}