<?php
// src/Controller/ReservationController.php
namespace App\Controller;

use App\Entity\Equipment;
use App\Entity\Reservation;
use App\Service\ReservationService;
use App\Repository\EquipmentRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use Symfony\Component\VarDumper\VarDumper;

#[Route('/reservations')]
#[IsGranted('ROLE_USER')]
class ReservationController extends AbstractController
{
    public function __construct(
        private ReservationRepository $reservationRepository,
        private EquipmentRepository $equipmentRepository,
        private ReservationService $reservationService,
        private LoggerInterface $logger
    ) {}

    #[Route('/', name: 'app_reservations')]
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $this->reservationRepository->findBy(['user' => $this->getUser()]),
            'current_menu' => 'reservations'
        ]);
    }

    #[Route('/equipments/{id}/new', name: 'app_reservation_new')]
    public function new(Equipment $equipment): Response
    {
        return $this->render('reservation/new.html.twig', [
            'equipment' => $equipment,
            'current_menu' => 'reservations'
        ]);
    }    

    #[Route('/create', name: 'app_reservation_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        try {
            $equipmentId = $request->request->get('equipment_id');
            $equipment = $this->equipmentRepository->find($equipmentId);
            
            if (!$equipment) {
                throw new \Exception('Equipment not found');
            }

            $startDate = new \DateTime($request->request->get('start_date'));
            $endDate = new \DateTime($request->request->get('end_date'));

            $reservation = $this->reservationService->createReservation(
                $equipment,
                $this->getUser(),
                $startDate,
                $endDate
            );

            $this->addFlash('success', 'Reservation created successfully');
            return $this->redirectToRoute('app_reservations');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('app_reservations');
        }
    }
    
    #[Route('/{id}/status', name: 'app_reservation_status', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function updateStatus(Reservation $reservation, Request $request): Response
    {
        $status = $request->request->get('status');
        $this->reservationService->updateReservationStatus($reservation, $status);
        
        return $this->redirectToRoute('app_reservations');
    }    
}
