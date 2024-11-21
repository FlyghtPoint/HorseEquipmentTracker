<?php

namespace App\Controller\Admin;

use App\Service\ApiClientService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    private $apiClient;

    public function __construct(ApiClientService $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        $equipmentData = $this->apiClient->getCollection('/equipment');
        $movementsData = $this->apiClient->getCollection('/movements');

        $totalEquipment = count($equipmentData);
        $totalMovements = count($movementsData);
    
        return $this->render('admin/dashboard/index.html.twig', [
            'totalEquipment' => $totalEquipment,
            'totalMovements' => $totalMovements
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Horse Equipment Tracker')
            ->setFaviconPath('favicon.svg');
    }
    
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        
        yield MenuItem::section('Équipement');
        yield MenuItem::linkToRoute('Équipements', 'fa fa-box', 'admin_equipment_index');
        yield MenuItem::linkToRoute('Catégories', 'fa fa-tags', 'admin_category_index');
        yield MenuItem::linkToRoute('États', 'fa fa-info-circle', 'admin_condition_index');
        yield MenuItem::linkToRoute('Emplacements', 'fa fa-map-marker-alt', 'admin_location_index');
        
        yield MenuItem::section('Gestion');
        yield MenuItem::linkToRoute('Réservations', 'fa fa-calendar', 'admin_reservation_index');
        yield MenuItem::linkToRoute('Mouvements', 'fa fa-exchange-alt', 'admin_movement_index');
        
        yield MenuItem::section('Administration');
        yield MenuItem::linkToRoute('Utilisateurs', 'fa fa-users', 'admin_user_index');
    }    
}