<?php
// src/Controller/ReservationController.php
namespace App\Controller;

use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/reservations')]
#[IsGranted('ROLE_USER')]
class ReservationController extends AbstractController
{
    public function __construct(
        private ReservationRepository $reservationRepository
    ) {}

    #[Route('/', name: 'app_reservations')]
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $this->reservationRepository->findBy(['user' => $this->getUser()]),
            'current_menu' => 'reservations'
        ]);
    }
}
