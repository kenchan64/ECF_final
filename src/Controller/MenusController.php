<?php

namespace App\Controller;

use App\Repository\MenuRepository;
use App\Repository\OpeningHoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenusController extends AbstractController
{

    #[Route('/menus', name: 'app_menus', methods: ['GET'])]
    public function index(): Response
    {
        $menus = $this->menuRepository->findAll();
        $openingHours = $this->openingHoursRepository->findAll();

        return $this->render('menus/index.html.twig', [
            'menu' => $menus,
            'openingHours' => $openingHours
        ]);
    }

    private MenuRepository $menuRepository;
    private OpeningHoursRepository $openingHoursRepository;

    public function __construct(MenuRepository $menuRepository, OpeningHoursRepository $openingHoursRepository)
    {
        $this->menuRepository = $menuRepository;
        $this->openingHoursRepository = $openingHoursRepository;
    }

}
