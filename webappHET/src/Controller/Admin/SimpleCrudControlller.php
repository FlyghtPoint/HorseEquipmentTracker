<?php

namespace App\Controller\Admin;

use App\Service\ApiClientService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SimpleCrudController extends AbstractController
{
    private $apiClient;

    public function __construct(ApiClientService $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    #[Route('/admin/categories', name: 'admin_category_index', methods: ['GET'])]
    public function listCategories(): Response
    {
        $categories = $this->apiClient->getCollection('/categories');

        return $this->render('admin/simple_crud/index.html.twig', [
            'items' => $categories,
            'title' => 'Catégories',
            'route_prefix' => 'category',
            'fields' => ['name']
        ]);
    }

    #[Route('/admin/conditions', name: 'admin_condition_index', methods: ['GET'])]
    public function listConditions(): Response
    {
        $conditions = $this->apiClient->getCollection('/conditions');

        return $this->render('admin/simple_crud/index.html.twig', [
            'items' => $conditions,
            'title' => 'États',
            'route_prefix' => 'condition',
            'fields' => ['name']
        ]);
    }

    #[Route('/admin/locations', name: 'admin_location_index', methods: ['GET'])]
    public function listLocations(): Response
    {
        $locations = $this->apiClient->getCollection('/locations');

        return $this->render('admin/simple_crud/index.html.twig', [
            'items' => $locations,
            'title' => 'Emplacements',
            'route_prefix' => 'location',
            'fields' => ['aisle', 'shelf_number']
        ]);
    }

    // Méthodes de création, modification et suppression à implémenter
    // #[Route('/admin/{entity}/new', name: 'admin_generic_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, string $entity): Response
    // {
    //     // Logique générique de création à définir
    // }

    // #[Route('/admin/{entity}/{id}/edit', name: 'admin_generic_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, string $entity, string $id): Response
    // {
    //     // Logique générique de modification à définir
    // }

    // #[Route('/admin/{entity}/{id}/delete', name: 'admin_generic_delete', methods: ['POST'])]
    // public function delete(Request $request, string $entity, string $id): Response
    // {
    //     // Logique générique de suppression à définir
    // }
}
