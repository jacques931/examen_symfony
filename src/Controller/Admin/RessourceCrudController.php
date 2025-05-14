<?php

namespace App\Controller\Admin;

use App\Entity\Ressource;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use App\Service\FileUploader;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RessourceCrudController extends AbstractCrudController
{
    private $parameterBag;
    private $fileUploader;
    
    public function __construct(ParameterBagInterface $parameterBag, FileUploader $fileUploader)
    {
        $this->parameterBag = $parameterBag;
        $this->fileUploader = $fileUploader;
    }
    
    public static function getEntityFqcn(): string
    {
        return Ressource::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('intitule')->setLabel('Intitulé'),
            TextEditorField::new('preseentation')->setLabel('Présentation'),
            ChoiceField::new('support')
                ->setChoices([
                    'PDF' => 'pdf',
                    'Image' => 'image',
                    'HTML' => 'html',
                    'Vidéo' => 'video'
                ])
                ->setHelp('Format du support de la ressource'),
            ChoiceField::new('nature')
                ->setChoices([
                    'Formulaire' => 'formulaire',
                    'Guide' => 'guide',
                    'QCM' => 'qcm',
                    'Autre' => 'autre'
                ])
                ->setHelp('Nature de la ressource'),
            ImageField::new('url_document')
                ->setLabel('Document')
                ->setBasePath('uploads/ressources')
                ->setUploadDir('public/uploads/ressources')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setFormTypeOption('required', $pageName === Crud::PAGE_NEW)
                ->setHelp('Téléchargez le document (PDF, image, etc.)'),
            AssociationField::new('etapes')
                ->setLabel('Étape associée')
                ->setRequired(true)
                ->setFormTypeOption('placeholder', 'Choisir une étape...')
                ->setHelp('Une ressource doit être associée à une étape'),
        ];
    }
    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Ressource')
            ->setEntityLabelInPlural('Ressources')
            ->setPageTitle('index', 'Liste des ressources')
            ->setPageTitle('new', 'Créer une ressource')
            ->setPageTitle('edit', 'Modifier une ressource')
            ->setPageTitle('detail', 'Détails de la ressource');
    }
    
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setLabel('Ajouter une ressource');
            })
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                return $action->setLabel('Voir');
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setLabel('Modifier');
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setLabel('Supprimer');
            });
    }
}
