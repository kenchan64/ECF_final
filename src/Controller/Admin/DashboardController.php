<?php

namespace App\Controller\Admin;

use App\Entity\Gallery;
use App\Entity\Menu;
use App\Entity\MenuCard;
use App\Entity\OpeningHours;
use App\Entity\RestaurantSettings;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin', methods: 'GET')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('ECF Site Restaurant');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Gallery', 'fa-regular fa-file-image', Gallery::class);
        yield MenuItem::linkToCrud('Menu', 'fa-solid fa-receipt', Menu::class);
        yield MenuItem::linkToCrud('MenuCard', 'fas fa-list', MenuCard::class);
        yield MenuItem::linkToCrud('OpeningHours', 'fa-regular fa-clock', OpeningHours::class);
        yield MenuItem::linkToCrud('RestaurantSettings', 'fas fa-cog', RestaurantSettings::class);
        yield MenuItem::linkToUrl('Reservations', 'fas fa-book', $this->generateUrl('app_reservation_show'));
        yield MenuItem::linkToUrl('Home', 'fa-solid fa-house', $this->generateUrl('home'));
    }
}
