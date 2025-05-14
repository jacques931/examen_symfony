<?php

namespace App\Controller;

use App\Entity\Etapes;
use App\Entity\Rendus;
use App\Form\RendusType;
use App\Repository\EtapesRepository;
use App\Repository\RendusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/submissions')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class RendusController extends AbstractController
{
    #[Route('/add', name: 'app_rendus_add', methods: ['POST'])]
    public function add(
        Request $request,
        EntityManagerInterface $entityManager,
        EtapesRepository $etapesRepository,
        SluggerInterface $slugger
    ): Response {
        // Vérifier si nous avons un fichier téléchargé
        if (!$request->files->has('document')) {
            $this->addFlash('error', 'Aucun fichier n\'a été téléchargé.');
            return $this->redirectToRoute('app_parcours_show', ['id' => $request->request->get('parcoursId')]);
        }
        
        // Récupérer l'étape
        $etapeId = $request->request->get('etapeId');
        $etape = $etapesRepository->find($etapeId);
        
        if (!$etape) {
            $this->addFlash('error', 'Étape non trouvée.');
            return $this->redirectToRoute('app_parcours_show', ['id' => $request->request->get('parcoursId')]);
        }
        
        // Initialiser un nouveau rendu avec les valeurs par défaut
        $rendus = new Rendus();
        $rendus->setDateHeure(new \DateTime()); // Date et heure actuelles
        $rendus->setUser($this->getUser()); // Utilisateur connecté
        $rendus->addEtape($etape); // Ajouter l'étape au rendu
        
        // Gérer le fichier téléchargé
        $documentFile = $request->files->get('document');
        if ($documentFile) {
            $originalFilename = pathinfo($documentFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$documentFile->guessExtension();
            
            try {
                $documentFile->move(
                    $this->getParameter('kernel.project_dir').'/public/uploads/rendus',
                    $newFilename
                );
                $rendus->setUrlDocument($newFilename);
            } catch (FileException $e) {
                $this->addFlash('error', 'Une erreur est survenue lors du téléchargement du fichier.');
                return $this->redirectToRoute('app_parcours_show', ['id' => $etape->getParcours()->getId()]);
            }
        }
        
        $entityManager->persist($rendus);
        $entityManager->flush();
        
        $this->addFlash('success', 'Votre rendu a été soumis avec succès.');
        
        return $this->redirectToRoute('app_parcours_show', ['id' => $etape->getParcours()->getId()]);
    }
    
    #[Route('/delete/{id}', name: 'app_rendus_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Rendus $rendus,
        EntityManagerInterface $entityManager
    ): Response {
        // Vérifier que l'utilisateur est bien le propriétaire du rendu
        if ($rendus->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer ce rendu.');
        }
        
        // Récupérer le parcours pour la redirection
        $etapes = $rendus->getEtapes()->first();
        $parcoursId = $etapes ? $etapes->getParcours()->getId() : null;
        
        // Supprimer le fichier physique
        $filePath = $this->getParameter('kernel.project_dir').'/public/uploads/rendus/'.$rendus->getUrlDocument();
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        // Supprimer l'entité
        $entityManager->remove($rendus);
        $entityManager->flush();
        
        $this->addFlash('success', 'Le rendu a été supprimé avec succès.');
        
        if ($parcoursId) {
            return $this->redirectToRoute('app_parcours_show', ['id' => $parcoursId]);
        } else {
            return $this->redirectToRoute('app_home');
        }
    }
    
    #[Route('/download/{id}', name: 'app_rendus_download', methods: ['GET'])]
    public function download(Rendus $rendus): Response
    {
        // Vérifier que l'utilisateur est autorisé à télécharger ce rendu
        if ($rendus->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à télécharger ce rendu.');
        }
        
        $filePath = $this->getParameter('kernel.project_dir').'/public/uploads/rendus/'.$rendus->getUrlDocument();
        
        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('Le fichier demandé n\'existe pas.');
        }
        
        return $this->file($filePath);
    }
}
