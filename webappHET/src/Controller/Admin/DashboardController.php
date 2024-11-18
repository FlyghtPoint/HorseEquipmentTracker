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
        $equipmentResponse = $this->apiClient->getCollection('/equipment');
        $movementsResponse = $this->apiClient->getCollection('/movements');
    
        // dump($equipmentResponse);
        // dump($movementsResponse);
        // var_dump($equipmentResponse);

        $totalEquipment = count($equipmentResponse);
        $totalMovements = count($movementsResponse);

        // $totalEquipment = isset($equipmentResponse['hydra:member']) 
        //     ? count($equipmentResponse['hydra:member']) 
        //     : 0;
        
        // $totalMovements = isset($movementsResponse['hydra:member']) 
        //     ? count($movementsResponse['hydra:member']) 
        //     : 0;
    
        return $this->render('admin/dashboard/index.html.twig', [
            'totalEquipment' => $totalEquipment,
            'totalMovements' => $totalMovements
        ]);
    }
}