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
            TextField::new('url_document')
                ->setLabel('Document')
                ->setFormType(\Symfony\Component\Form\Extension\Core\Type\FileType::class)
                ->setFormTypeOptions([
                    'mapped' => false,
                    'required' => $pageName === Crud::PAGE_NEW,
                    'constraints' => [
                        new \Symfony\Component\Validator\Constraints\File([
                            'maxSize' => '10M',
                            'mimeTypes' => [
                                'application/pdf',
                                'image/jpeg',
                                'image/png',
                                'image/gif',
                                'text/html',
                                'video/mp4'
                            ],
                            'mimeTypesMessage' => 'Veuillez télécharger un document valide (PDF, image, HTML, vidéo)',
                        ])
                    ],
                    'attr' => ['accept' => '.pdf,.jpg,.jpeg,.png,.gif,.html,.mp4']
                ])
                ->setHelp('Téléchargez le document (PDF, image, etc.)')
                ->setCustomOption('uploadDir', 'public/uploads/ressources')
                ->setCustomOption('uploadPath', 'uploads/ressources')
                ->onlyOnForms(),
            TextField::new('url_document')
                ->setLabel('Document')
                ->formatValue(function ($value, $entity) {
                    if (!$value) {
                        return '';
                    }
                    $basePath = '/uploads/ressources';
                    $mimeType = pathinfo($value, PATHINFO_EXTENSION);
                    if (in_array($mimeType, ['pdf'])) {
                        return sprintf('<a href="%s" target="_blank">Voir le PDF</a>', $basePath.'/'.$value);
                    } elseif (in_array($mimeType, ['jpg', 'jpeg', 'png', 'gif'])) {
                        return sprintf('<img src="%s" width="100" />', $basePath.'/'.$value);
                    } elseif (in_array($mimeType, ['mp4'])) {
                        return sprintf('<video width="320" height="240" controls><source src="%s" type="video/mp4"></video>', $basePath.'/'.$value);
                    } else {
                        return sprintf('<a href="%s" target="_blank">Voir le document</a>', $basePath.'/'.$value);
                    }
                })
                ->onlyOnIndex(),
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
    
    public function createEntity(string $entityFqcn)
    {
        $ressource = new Ressource();
        return $ressource;
    }
    
    public function updateEntity(\Doctrine\ORM\EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->processUpload($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }
    
    public function persistEntity(\Doctrine\ORM\EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->processUpload($entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }
    
    private function processUpload($ressource): void
    {
        $uploadedFile = $this->getContext()->getRequest()->files->get('Ressource')['url_document'] ?? null;
        
        if ($uploadedFile) {
            $newFilename = $this->fileUploader->upload($uploadedFile);
            $ressource->setUrlDocument($newFilename);
        }
    }
}
