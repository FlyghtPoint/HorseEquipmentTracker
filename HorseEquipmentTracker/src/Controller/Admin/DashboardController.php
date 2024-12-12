<?php

namespace App\Controller\Admin;

use App\Entity\Equipment;
use App\Entity\Movement;
use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\Condition;
use App\Entity\Location;
use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(EquipmentCrudController::class)->generateUrl());
        // $equipmentData = $this->apiClient->getCollection('/equipment');
        // $movementsData = $this->apiClient->getCollection('/movements');

        // $totalEquipment = count($equipmentData);
        // $totalMovements = count($movementsData);
    
        // return $this->render('admin/dashboard/index.html.twig', [
        //     'totalEquipment' => $totalEquipment,
        //     'totalMovements' => $totalMovements
        // ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Horse Equipment Tracker')
            ->setFaviconPath('favicon.svg')
            ->renderContentMaximized();
    }
    
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
     
        yield MenuItem::section('Gestion des équipements');
        yield MenuItem::linkToCrud('Équipements', 'fas fa-box', Equipment::class);
        // yield MenuItem::linkToCrud('Mouvements', 'fas fa-exchange-alt', Movement::class);
        // yield MenuItem::linkToCrud('Réservations', 'fas fa-calendar', Reservation::class);

        yield MenuItem::section('Configuration');
        // yield MenuItem::linkToCrud('États', 'fas fa-thermometer-half', Condition::class);
        // yield MenuItem::linkToCrud('Emplacements', 'fas fa-map-marker', Location::class);
        // yield MenuItem::linkToCrud('Catégories', 'fas fa-tags', Category::class);
        
        yield MenuItem::section('Utilisateurs');
        // yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
        
        yield MenuItem::section('Système');
        yield MenuItem::linkToRoute('Retour au site', 'fas fa-home', 'app_home');
    }    
}