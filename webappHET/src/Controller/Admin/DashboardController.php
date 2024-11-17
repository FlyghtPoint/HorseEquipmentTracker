<?php
// src/Controller/Admin/DashboardController.php
namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractController
{
    #[Route('', name: 'admin_dashboard')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'stats' => $this->getDashboardStats(),
        ]);
    }

    private function getDashboardStats(): array
    {
        // Implement your stats logic here
        return [
            'total_products' => 0,
            'low_stock_items' => 0,
            // ...
        ];
    }
}