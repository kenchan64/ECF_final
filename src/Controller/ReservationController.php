<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\OpeningHoursRepository;
use App\Repository\ReservationRepository;
use App\Repository\RestaurantSettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ReservationController extends AbstractController
{
    private OpeningHoursRepository $openingHoursRepository;
    private ReservationRepository $reservationRepository;
    private RestaurantSettingsRepository $restaurantSettingsRepository;

    public function __construct(
        OpeningHoursRepository       $openingHoursRepository,
        ReservationRepository        $reservationRepository,
        RestaurantSettingsRepository $restaurantSettingsRepository,
    )
    {
        $this->openingHoursRepository = $openingHoursRepository;
        $this->reservationRepository = $reservationRepository;
        $this->restaurantSettingsRepository = $restaurantSettingsRepository;
    }

    #[Route('/reservations', name: 'app_reservation_show', methods: ['GET'])]
    public function showAllReservations(): Response
    {
        $reservations = $this->reservationRepository->findAll();
        $openingHours = $this->openingHoursRepository->findAll();
        $reservationId = null;
        $reservation = $this->reservationRepository->findAll();
        if (!empty($reservation)) {
            $reservationId = $reservation[0]->getId();
        }

        return $this->render('reservation/reservations/show.html.twig', [
            'reservations' => $reservations,
            'openingHours' => $openingHours,
            'reservationId' => $reservationId ?? null,
        ]);
    }

    #[Route('/reservation', name: 'app_reservation', methods: ['GET', 'POST'])]
    public function index(Request $request, RestaurantSettingsRepository $restaurantSettingsRepository, Session $session): Response
    {
        $reservation = new Reservation();
        $restaurantSettings = $restaurantSettingsRepository->getRestaurantSettings();
        $maxGuests = $restaurantSettings ? $restaurantSettings->getMaxGuests() : 0;
        $form = $this->createForm(ReservationType::class, $reservation);

        if ($this->getUser() !== null) {
            $user = $this->getUser();
            $form->get('nbCouverts')->setData($user->getDefaultGuests());
            $form->get('allergies')->setData($user->getAllergies());
        }

        $form->handleRequest($request);
        $openingHours = $this->openingHoursRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $date = $reservation->getDate();
            $timeSlot = clone $date;
            $nbCouverts = $reservation->getNbCouverts();

            $totalReservedSeats = $this->reservationRepository->findTotalSeatsReservedByCriteria($timeSlot);

            if ($totalReservedSeats + $reservation->getNbCouverts() <= $maxGuests) {
                $this->reservationRepository->save($reservation);

                $this->addFlash('success', 'Merci pour votre réservation!');

                return $this->redirectToRoute('home');
            } else {
                $this->addFlash('danger', 'Pas assez de places disponibles');
            }
        }

        return $this->render('reservation/index.html.twig', [
            'openingHours' => $openingHours,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reservation/seats', name: 'app_reservation_seats', methods: ['POST'])]
    public function getAvailableSeats(Request $request): JsonResponse
    {
        if ($request->isMethod('POST')) {
            $data = json_decode($request->getContent(), true);
            $date = new \DateTime($data['date']);
            $timeSlot = clone $date;
            $nbCouverts = $data['nbCouverts'];

            $totalReservedSeats = $this->reservationRepository->findTotalSeatsReservedByCriteria($timeSlot, $nbCouverts);
            $restaurantSettings = $this->restaurantSettingsRepository->getRestaurantSettings();
            $totalSeats = $restaurantSettings ? $restaurantSettings->getMaxGuests() : 0;
            $availableSeats = $totalSeats - $totalReservedSeats;

        return new JsonResponse(['availableSeats' => $availableSeats, 'totalSeats' => $totalSeats]);
    }
        return new JsonResponse('Invalid request', 400);
    }

    #[Route('/reservation/delete', name: 'reservation_delete', methods: ['POST'])]
    public function deleteMultiple(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(('ROLE_ADMIN'));

        // Check if the request method is POST
        if (!$request->isMethod('POST')) {
            throw $this->createAccessDeniedException('Invalid request method.');
        }

        $data = json_decode($request->getContent(), true);
        $selectedReservations = $data['reservations'];

        if (!is_array($selectedReservations)) {
            throw new BadRequestHttpException('Invalid reservations data.');
        }

        foreach ($selectedReservations as $reservationId) {
            // Retrieve the reservation from the database
            $reservation = $entityManager->getRepository(Reservation::class)->find($reservationId);

            // Check if the reservation exists
            if (!$reservation) {
                throw $this->createNotFoundException('Reservation not found.');
            }

            // Delete the reservation from the database
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        // Redirect to a success page or return a response
        return $this->redirectToRoute('app_reservation_show');
    }
}