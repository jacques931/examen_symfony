<?php

namespace App\Controller\Admin;

use App\Entity\Etapes;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class EtapesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Etapes::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('descriptif'),
            TextEditorField::new('consignes'),
            IntegerField::new('position'),
            AssociationField::new('parcours')
                ->setRequired(true)
                ->setFormTypeOption('placeholder', 'Choisir un parcours...')
                ->setHelp('Une étape doit obligatoirement être associée à un parcours'),
        ];
    }
    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Étape')
            ->setEntityLabelInPlural('Étapes')
            ->setPageTitle('index', 'Liste des étapes')
            ->setPageTitle('new', 'Créer une étape')
            ->setPageTitle('edit', 'Modifier une étape')
            ->setPageTitle('detail', 'Détails de l\'étape');
    }
}
