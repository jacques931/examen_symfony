<?php

namespace App\Controller\Admin;

use App\Entity\Parcours;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ParcoursCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Parcours::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('objet'),
            TextEditorField::new('description'),
            AssociationField::new('user')
                ->setLabel('Utilisateurs')
                ->setFormTypeOption('by_reference', false)
                ->setFormTypeOption('multiple', true)
                ->setHelp('Sélectionnez un ou plusieurs utilisateurs à associer à ce parcours'),
        ];
    }
}
