<?php

namespace App\Form;

use App\Entity\Message;
use App\Entity\Rendus;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityRepository;

class MessageType extends AbstractType
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentUser = $this->tokenStorage->getToken()->getUser();

        $builder
            ->add('destinataire', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return $user->getPrenom() . ' ' . $user->getNom() . ' (' . $user->getEmail() . ')';
                },
                'label' => 'Destinataire',
                'attr' => [
                    'class' => 'form-select'
                ],
                'query_builder' => function (EntityRepository $er) use ($currentUser) {
                    return $er->createQueryBuilder('u')
                        ->where('u.id != :currentUserId')
                        ->setParameter('currentUserId', $currentUser->getId())
                        ->orderBy('u.nom', 'ASC');
                }
            ])
            ->add('renduses', EntityType::class, [
                'class' => Rendus::class,
                'choice_label' => function (Rendus $rendus) {
                    $etapesLabels = [];
                    foreach ($rendus->getEtapes() as $etape) {
                        $etapesLabels[] = $etape->getDescriptif();
                    }
                    $etapesText = !empty($etapesLabels) ? ' - Étapes: ' . implode(', ', $etapesLabels) : '';
                    return 'Rendu du ' . $rendus->getDateHeure()->format('d/m/Y H:i') . $etapesText;
                },
                'label' => 'Rendu associé',
                'attr' => [
                    'class' => 'form-select'
                ],
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'query_builder' => function (EntityRepository $er) use ($currentUser) {
                    return $er->createQueryBuilder('r')
                        ->where('r.user = :user')
                        ->setParameter('user', $currentUser)
                        ->orderBy('r.dateHeure', 'DESC');
                }
            ])
            ->add('titre', TextType::class, [
                'label' => 'Objet',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez l\'objet du message'
                ]
            ])
            ->add('contenu', TextareaType::class, [
                'label' => 'Contenu',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                    'placeholder' => 'Entrez le contenu du message'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
