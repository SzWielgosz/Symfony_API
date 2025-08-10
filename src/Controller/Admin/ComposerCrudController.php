<?php

namespace App\Controller\Admin;

use App\Entity\Composer;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ComposerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Composer::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('firstName'),
            TextField::new('lastName'),
            DateTimeField::new('dateOfBirth')
                ->setFormat('Y-m-d')
                ->setTimezone('UTC'),
            TextField::new('countryCode')
                ->setLabel('Country Code')
                ->setHelp('Use the ISO 3166-1 alpha-2 country code (e.g., US, FR, DE)')
                ->setMaxLength(2),
            CollectionField::new('symphonies')
                ->useEntryCrudForm(SymphonyCrudController::class)
        ];
    }
}
