<?php

namespace App\Controller\Admin;

use App\Entity\OpeningHours;
use Doctrine\DBAL\Types\IntegerType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;


class OpeningHoursCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OpeningHours::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Jours d\'ouverture')
            ->setEntityLabelInSingular('Jour d\'ouverture')
            ->setEntityLabelInPlural('Jours d\'ouverture')
            ->setDefaultSort(['day' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        $isClosed = BooleanField::new('closed', 'Fermé');
        $dayField = TextField::new('day', 'Jour');
        $middayOpenField = TimeField::new('middayOpen', 'Ouverture midi')
            ->setFormTypeOption('required', false);
        $middayCloseField = TimeField::new('middayClose', 'Fermeture midi')
            ->setFormTypeOption('required', false);
        $eveningOpenField = TimeField::new('eveningOpen', 'Ouverture soir')
            ->setFormTypeOption('required', false);
        $eveningCloseField = TimeField::new('eveningClose', 'Fermeture soir')
            ->setFormTypeOption('required', false);


        yield $isClosed;
        yield $dayField;
        yield $middayOpenField;
        yield $middayCloseField;
        yield $eveningOpenField;
        yield $eveningCloseField;
    }
}
