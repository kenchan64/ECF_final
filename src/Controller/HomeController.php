<?php

namespace App\Controller;

use App\Repository\GalleryRepository;
use App\Repository\OpeningHoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(): Response
    {
        $galleryImages = $this->galleryRepository->findAll();
        $openingHours = $this->openingHoursRepository->findAll();
        // Render the template with the gallery images
        return $this->render('home/index.html.twig', [
            'gallery' => $galleryImages,
            'openingHours' => $openingHours
        ]);
    }

    private GalleryRepository $galleryRepository;
    private OpeningHoursRepository $openingHoursRepository;

    public function __construct(GalleryRepository $galleryRepository, OpeningHoursRepository $openingHoursRepository)
    {
        $this->galleryRepository = $galleryRepository;
        $this->openingHoursRepository = $openingHoursRepository;
    }
}
