<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Repository\RendusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/message')]
class MessageController extends AbstractController
{
    #[Route('/', name: 'app_message_index', methods: ['GET'])]
    public function index(MessageRepository $messageRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }
        
        // Récupérer les messages dont l'utilisateur est l'émetteur ou le destinataire
        $messages = $messageRepository->findMessagesForUser($user);
        
        return $this->render('message/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    #[Route('/new', name: 'app_message_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour envoyer un message.');
        }
        
        $message = new Message();
        $message->setDateheure(new \DateTime());
        $message->setEmetteur($user);
        
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persister d'abord le message
            $entityManager->persist($message);
            
            // Récupérer les rendus sélectionnés dans le formulaire
            $selectedRendus = $form->get('renduses')->getData();
            
            // Parcourir tous les rendus sélectionnés
            foreach ($selectedRendus as $rendu) {
                // Ajouter explicitement le message au rendu
                if (!$rendu->getMessages()->contains($message)) {
                    $rendu->addMessage($message);
                    $entityManager->persist($rendu);
                }
            }
            
            // Enregistrer les modifications
            $entityManager->flush();

            $this->addFlash('success', 'Votre message a été envoyé avec succès.');
            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('message/new.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_message_show', methods: ['GET'])]
    public function show(Message $message, RendusRepository $rendusRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour voir ce message.');
        }
        
        // Vérifier que l'utilisateur est l'émetteur ou le destinataire du message
        if ($message->getEmetteur() !== $user && $message->getDestinataire() !== $user) {
            throw new AccessDeniedException('Vous n\'avez pas accès à ce message.');
        }
        
        // Récupérer les rendus associés à ce message
        $rendus = $rendusRepository->createQueryBuilder('r')
            ->join('r.messages', 'm')
            ->where('m.id = :messageId')
            ->setParameter('messageId', $message->getId())
            ->getQuery()
            ->getResult();
        
        return $this->render('message/show.html.twig', [
            'message' => $message,
            'rendus' => $rendus,
        ]);
    }
}
