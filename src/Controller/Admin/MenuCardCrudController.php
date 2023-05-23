<?php

namespace App\Controller\Admin;

use App\Entity\MenuCard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MenuCardCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MenuCard::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Carte');
    }
}
