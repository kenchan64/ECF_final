<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\OpeningHoursRepository;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(): Response
    {
        $openingHours = $this->openingHoursRepository->findAll();
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
            'openingHours' => $openingHours
        ]);
    }
    private OpeningHoursRepository $openingHoursRepository;

    public function __construct(OpeningHoursRepository $openingHoursRepository)
    {
        $this->openingHoursRepository = $openingHoursRepository;
    }

    /*public function reservation(Request $request): Response
    {
        $reservation = new Reservation();

        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Gérez la soumission du formulaire ici
            // Par exemple, enregistrez la réservation en base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            // Redirigez l'utilisateur vers une autre page (par exemple, une page de confirmation)
            return $this->redirectToRoute('home');
        }

        return $this->render(':reservation:index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function getDoctrine()
    {
    }*/
}
