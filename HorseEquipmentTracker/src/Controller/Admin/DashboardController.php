<?php
// src/Controller/Admin/DashboardController.php

namespace App\Controller\Admin;

use App\Entity\Equipment;
// use App\Entity\Category;
// use App\Entity\Location;
use App\Entity\Reservation;
use App\Entity\User;
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
            ->setTitle('Gestion de sellerie')
            ->setFaviconPath('favicon.svg')
            ->renderContentMaximized();
    }
    
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
     
        yield MenuItem::section('Inventaire');
        yield MenuItem::linkToCrud('Équipements', 'fas fa-box', Equipment::class);
        // yield MenuItem::linkToCrud('Catégories', 'fas fa-tags', Category::class);
        // yield MenuItem::linkToCrud('Emplacements', 'fas fa-map-marker', Location::class);

        yield MenuItem::section('Mouvements');
        yield MenuItem::subMenu('Réservations', 'fas fa-calendar')->setSubItems([
            MenuItem::linkToCrud('Toutes', 'fas fa-list', Reservation::class),
            MenuItem::linkToCrud('Prêts', 'fas fa-hand-holding', Reservation::class)
                ->setController(LoanReservationCrudController::class),
            MenuItem::linkToCrud('Réparations', 'fas fa-wrench', Reservation::class)
                ->setController(RepairReservationCrudController::class),
        ]);

        yield MenuItem::section('Rapports');
        yield MenuItem::linkToRoute('Historique', 'fas fa-history', 'admin_history');
        yield MenuItem::linkToRoute('Statistiques', 'fas fa-chart-bar', 'admin_stats');
        
        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
        
        yield MenuItem::section('Système');
        yield MenuItem::linkToRoute('Retour au site', 'fas fa-home', 'app_home');
    }    
}