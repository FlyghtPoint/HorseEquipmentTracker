<?php

namespace App\Controller\Admin;

use App\Service\ApiClientService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/equipment')]
class EquipmentController extends AbstractController
{
    private $apiClient;

    public function __construct(ApiClientService $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    #[Route('/', name: 'admin_equipment_index', methods: ['GET'])]
    public function index(): Response
    {
        $equipments = $this->apiClient->getCollection('/equipment');

        return $this->render('admin/equipment/index.html.twig', [
            'equipments' => $equipments
        ]);
    }

    #[Route('/new', name: 'admin_equipment_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            $response = $this->apiClient->create('/equipment', $data);

            if ($response) {
                $this->addFlash('success', 'Equipment created successfully');
                return $this->redirectToRoute('admin_equipment_index');
            }
        }

        // Fetch necessary related data for forms (conditions, locations, categories)
        $conditions = $this->apiClient->getCollection('/conditions');
        $locations = $this->apiClient->getCollection('/locations');
        $categories = $this->apiClient->getCollection('/categories');

        return $this->render('admin/equipment/new.html.twig', [
            'conditions' => $conditions,
            'locations' => $locations,
            'categories' => $categories
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_equipment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, string $id): Response
    {
        $equipment = $this->apiClient->getItem('/equipment', $id);

        if ($request->isMethod('POST')) {
            $data = $request->request->all();
            
            try {
                $this->apiClient->update('/equipment', $id, $data);
                $this->addFlash('success', 'Equipment updated successfully');
                return $this->redirectToRoute('admin_equipment_index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Failed to update equipment: ' . $e->getMessage());
            }
        }

        // Fetch necessary related data for forms
        $conditions = $this->apiClient->getCollection('/conditions');
        $locations = $this->apiClient->getCollection('/locations');
        $categories = $this->apiClient->getCollection('/categories');

        return $this->render('admin/equipment/edit.html.twig', [
            'equipment' => $equipment,
            'conditions' => $conditions,
            'locations' => $locations,
            'categories' => $categories
        ]);
    }

    #[Route('/{id}', name: 'admin_equipment_delete', methods: ['POST'])]
    public function delete(Request $request, string $id): Response
    {
        if ($this->isCsrfTokenValid('delete'.$id, $request->request->get('_token'))) {
            try {
                $this->apiClient->delete('/equipment', $id);
                $this->addFlash('success', 'Equipment deleted successfully');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Failed to delete equipment: ' . $e->getMessage());
            }
        }

        return $this->redirectToRoute('admin_equipment_index');
    }
}