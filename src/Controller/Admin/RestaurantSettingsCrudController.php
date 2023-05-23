<?php

namespace App\Controller\Admin;

use App\Entity\RestaurantSettings;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RestaurantSettingsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RestaurantSettings::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Maximum de couverts');
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
