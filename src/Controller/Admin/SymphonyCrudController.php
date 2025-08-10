<?php

namespace App\Controller\Admin;

use App\Entity\Symphony;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SymphonyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Symphony::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('name'),
            TextEditorField::new('description'),
            AssociationField::new('tags')
                ->setFormTypeOption('by_reference', false)
                ->setFormTypeOption('choice_label', 'name')
                ->setFormTypeOption('multiple', true)
                ->setRequired(true)
                ->setHelp('Select at least one tag for the symphony.'),
            AssociationField::new('composer')
                ->setFormTypeOption('choice_label', function($composer) {
                    return sprintf('%s %s', $composer->getFirstName(), $composer->getLastName());
                })
        ];
    }
}
