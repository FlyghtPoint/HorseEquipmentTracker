<?php
// src/Controller/EquipmentController.php
namespace App\Controller;

use App\Repository\EquipmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/equipments')]
class EquipmentController extends AbstractController
{
    public function __construct(
        private EquipmentRepository $equipmentRepository
    ) {}

    #[Route('/', name: 'app_equipment_list')]
    public function index(): Response
    {
        return $this->render('equipment/index.html.twig', [
            'equipments' => $this->equipmentRepository->findAll(),
            'current_menu' => 'equipment'
        ]);
    }

    #[Route('/{id}', name: 'app_equipment_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $equipment = $this->equipmentRepository->find($id);
        
        if (!$equipment) {
            throw $this->createNotFoundException('Équipement non trouvé');
        }

        return $this->render('equipment/show.html.twig', [
            'equipment' => $equipment,
            'current_menu' => 'equipment'
        ]);
    }
}
