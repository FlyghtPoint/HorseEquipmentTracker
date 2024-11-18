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

    #[Route('/', name: 'admin_equipment_index')]
    public function index(): Response
    {
        $equipments = $this->apiClient->getCollection('/equipments');

        return $this->render('admin/equipment/index.html.twig', [
            'equipments' => $equipments['hydra:member']
        ]);
    }

    #[Route('/new', name: 'admin_equipment_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $data = $request->request->all();
            
            try {
                $this->apiClient->create('/equipments', $data);
                $this->addFlash('success', 'Equipment created successfully');
                return $this->redirectToRoute('admin_equipment_index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Failed to create equipment: ' . $e->getMessage());
            }
        }

        // Fetch necessary related data for forms (conditions, locations, categories)
        $conditions = $this->apiClient->getCollection('/conditions');
        $locations = $this->apiClient->getCollection('/locations');
        $categories = $this->apiClient->getCollection('/categories');

        return $this->render('admin/equipment/new.html.twig', [
            'conditions' => $conditions['hydra:member'],
            'locations' => $locations['hydra:member'],
            'categories' => $categories['hydra:member']
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_equipment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, string $id): Response
    {
        $equipment = $this->apiClient->getItem('/equipments', $id);

        if ($request->isMethod('POST')) {
            $data = $request->request->all();
            
            try {
                $this->apiClient->update('/equipments', $id, $data);
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
            'conditions' => $conditions['hydra:member'],
            'locations' => $locations['hydra:member'],
            'categories' => $categories['hydra:member']
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_equipment_delete')]
    public function delete(string $id): Response
    {
        try {
            $this->apiClient->delete('/equipments', $id);
            $this->addFlash('success', 'Equipment deleted successfully');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Failed to delete equipment: ' . $e->getMessage());
        }

        return $this->redirectToRoute('admin_equipment_index');
    }
}