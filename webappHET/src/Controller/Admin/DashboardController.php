<?php
namespace App\Controller\Admin;

use App\Service\ApiClientService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    private $apiClient;

    public function __construct(ApiClientService $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        // Fetch some key metrics
        $equipmentData = $this->apiClient->getCollection('/equipment');
        $movementsData = $this->apiClient->getCollection('/movements');

        $totalEquipment = count($equipmentData);
        $totalMovements = count($movementsData);
    
        return $this->render('admin/dashboard/index.html.twig', [
            'totalEquipment' => $totalEquipment,
            'totalMovements' => $totalMovements
        ]);
    }
}