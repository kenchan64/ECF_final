<?php

namespace App\Controller;

use App\Repository\OpeningHoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private OpeningHoursRepository $openingHoursRepository;

    public function __construct(OpeningHoursRepository $openingHoursRepository)
    {
        $this->openingHoursRepository = $openingHoursRepository;
    }

    #[Route(path: '/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_reservation');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $openingHours = $this->openingHoursRepository->findAll();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error,
            'openingHours' => $openingHours]);
    }

    #[Route(path: '/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): Response
    {
        return $this->redirectToRoute('home');
    }

}
