<?php

namespace App\Controller;

use App\Repository\MenuCardRepository;
use App\Repository\OpeningHoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarteDishes extends AbstractController
{


    #[Route('/carte', name: 'app_page', methods: ['GET'])]
    public function index(): Response
    {
        $menuCards = $this->menuCardRepository->findAll();
        $openingHours = $this->openingHoursRepository->findAll();

        return $this->render('page/index.html.twig', [
            'carte' => $menuCards,
            'openingHours' => $openingHours
        ]);
    }
    private MenuCardRepository $menuCardRepository;
    private OpeningHoursRepository $openingHoursRepository;

    public function __construct(MenuCardRepository $menuCardRepository, OpeningHoursRepository $openingHoursRepository)
    {
        $this->menuCardRepository = $menuCardRepository;
        $this->openingHoursRepository = $openingHoursRepository;
    }
}
