<?php
namespace App\Controller\Admin;

use App\Service\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class SecurityController extends AbstractController
{
    #[Route('/admin/login', name: 'admin_login')]
    public function adminLogin(
        Request $request, 
        AuthenticationUtils $authenticationUtils,
        AuthService $authService
    ): Response {
        // Redirect if already logged in as admin
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_dashboard');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($request->isMethod('POST')) {
            $email = $request->request->get('_username');
            $password = $request->request->get('_password');

            try {
                $token = $authService->login($email, $password);
                
                if ($token && $this->isGranted('ROLE_ADMIN')) {
                    return $this->redirectToRoute('admin_dashboard');
                } else {
                    $error = new AuthenticationException('You do not have admin privileges.');
                }
            } catch (AuthenticationException $e) {
                $error = $e;
            }
        }

        return $this->render('admin/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authUtils): Response 
    {
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(AuthService $authService): Response
    {
        $authService->logout();
        return $this->redirectToRoute('app_login');
    }

    #[Route('/admin/logout', name: 'admin_logout')]
    public function adminLogout(AuthService $authService): Response
    {
        $authService->logout();
        return $this->redirectToRoute('admin_login');
    }

    #[Route('/register', name: 'app_register')]
    public function register(): Response
    {
        return $this->render('security/register.html.twig');
    }
}