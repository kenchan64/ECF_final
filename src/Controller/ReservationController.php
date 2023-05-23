<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\OpeningHoursRepository;
use App\Repository\ReservationRepository;
use App\Repository\RestaurantSettingsRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    private OpeningHoursRepository $openingHoursRepository;

    private ReservationRepository $reservationRepository;
    private RestaurantSettingsRepository $restaurantSettingsRepository;

    private bool $previousAvailability = false;

    public function __construct(
        OpeningHoursRepository       $openingHoursRepository,
        ReservationRepository        $reservationRepository,
        RestaurantSettingsRepository $restaurantSettingsRepository
    )
    {
        $this->openingHoursRepository = $openingHoursRepository;
        $this->reservationRepository = $reservationRepository;
        $this->restaurantSettingsRepository = $restaurantSettingsRepository;
    }

    #[Route('/reservations', name: 'app_reservation_show')]
    public function showAllReservations(): Response
    {
        $reservations = $this->reservationRepository->findAll();
        $openingHours = $this->openingHoursRepository->findAll();

        return $this->render('reservation/reservations/show.html.twig', [
            'reservations' => $reservations,
            'openingHours' => $openingHours,
        ]);
    }

    #[Route('/reservation', name: 'app_reservation')]
    public function index(Request $request): Response
    {
        $openingHours = $this->openingHoursRepository->findAll();
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $restaurantSettings = $this->restaurantSettingsRepository->findOneBy([]);
        $reservation->setRestaurantSettings($restaurantSettings);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $totalGuestsForTime = $this->reservationRepository->getTotalGuestsForTime($reservation->getHeure(), $reservation->getDate());
            $maxGuests = $restaurantSettings ? $restaurantSettings->getMaxGuests() : 0;

            if ($reservation->getNbCouverts() + $totalGuestsForTime > $maxGuests) {
            // Not enough seats available
                $this->addFlash('danger', 'Not enough seats available');
                return $this->redirectToRoute('app_reservation');
            }

            $this->reservationRepository->save($reservation);

            return $this->redirectToRoute('home');
        }

        // Récupérer la disponibilité à partir de checkAvailability
        $availability = $this->checkAvailability($request);
        $availability = json_decode($availability, true);

        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
            'openingHours' => $openingHours,
            'form' => $form->createView(),
            'available' => $availability['available'] ?? false,
            'isAvailable' => $availability['isAvailable'] ?? false,
            'remainingSeats' => $availability['remainingSeats'] ?? 0,
            'reservation' => $reservation,
        ]);
    }


    #[Route('/reservation/check-availability', name: 'app_reservation_check_availability', methods: ['POST'])]
    public function checkAvailability(Request $request): JsonResponse
    {
        $data = $request->request->all();
        $nbCouverts = $data['nbCouverts'] ?? null;
        $heure = $data['heure'] ?? null;
        $date = $data['date'] ?? null;

        // S'il manque des données, nous ne pouvons pas vérifier la disponibilité.
        if ($nbCouverts === null || $heure === null || $date === null) {
            return new JsonResponse(['available' => true, 'remainingSeats' => 0, 'availabilityChanged' => false]);
        }

        $remainingSeats = $this->calculateRemainingSeats($heure, $date);
        $isAvailable = $remainingSeats >= $nbCouverts;
        $availabilityChanged = $isAvailable;

        $responseData = [
            'available' => $isAvailable,
            'remainingSeats' => $remainingSeats,
            'availabilityChanged' => $availabilityChanged,
        ];

        return new JsonResponse($responseData);
    }

    #[Route('/reservation/{id}/delete', name: 'reservation_delete', methods: ['DELETE'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(('ROLE_ADMIN'));
        // Check if the request method is DELETE or POST
        if (!$request->isMethod('DELETE') && !$request->isMethod('POST')) {
            throw $this->createAccessDeniedException('Invalid request method.');
        }

        // Check if the reservation exists
        if (!$reservation) {
            throw $this->createNotFoundException('Reservation not found.');
        }

        // Delete the reservation from the database
        $entityManager->remove($reservation);
        $entityManager->flush();

        // Redirect to a success page or return a response
        return $this->redirectToRoute('app_reservation_show');
    }

    private function calculateRemainingSeats($heure, $date): int
    {
        $restaurantSettings = $this->restaurantSettingsRepository->findOneBy([]);
        $maxGuests = $restaurantSettings ? $restaurantSettings->getMaxGuests() : 0;

        if ($heure instanceof DateTime || $heure === null) {
            $totalGuestsForTime = $this->reservationRepository->getTotalGuestsForTime($heure, $date);
            $remainingSeats = $maxGuests - $totalGuestsForTime;
            return $remainingSeats >= 0 ? $remainingSeats : 0;
        } else {
            return 0;
        }
    }

    private function isReservationAvailable(int $nbCouverts, $heure, $date): bool
    {
        $restaurantSettings = $this->restaurantSettingsRepository->findOneBy([]);
        $maxGuests = $restaurantSettings ? $restaurantSettings->getMaxGuests() : 0;

        if ($heure instanceof DateTime || $heure === null) {
            $totalGuestsForTime = $this->reservationRepository->getTotalGuestsForTime($heure, $date);
            return $nbCouverts + $totalGuestsForTime <= $maxGuests;
        } else {
            return false;
        }
    }

    private function checkAvailabilityChanged($nbCouverts, $heure, $date): bool
    {
        $isAvailable = $this->isReservationAvailable($nbCouverts, $heure, $date);
        $availabilityChanged = $isAvailable !== $this->previousAvailability;
        $this->previousAvailability = $isAvailable;

        return $availabilityChanged;
    }
}

