<?php

namespace App\Controller;

use App\Entity\Ressource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RessourceController extends AbstractController
{
    #[Route('/ressource/download/{id}', name: 'app_ressource_download')]
    public function download(Ressource $ressource): Response
    {
        // Récupérer le chemin du fichier
        $fileName = $ressource->getUrlDocument();
        $filePath = $this->getParameter('kernel.project_dir') . '/public/uploads/ressources/' . $fileName;
        
        // Vérifier si le fichier existe
        if (!file_exists($filePath)) {
            throw new NotFoundHttpException('Le document demandé n\'existe pas.');
        }
        
        // Créer une réponse binaire avec le fichier
        $response = new BinaryFileResponse($filePath);
        
        // Déterminer le type MIME en fonction de l'extension
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $mimeType = match (strtolower($extension)) {
            'pdf' => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'html', 'htm' => 'text/html',
            'mp4' => 'video/mp4',
            default => 'application/octet-stream',
        };
        
        // Configurer les en-têtes pour le téléchargement
        $response->headers->set('Content-Type', $mimeType);
        
        // Générer un nom de fichier convivial basé sur l'intitulé de la ressource
        $safeFilename = $this->slugify($ressource->getIntitule()) . '.' . $extension;
        
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $safeFilename
        );
        
        $response->headers->set('Content-Disposition', $disposition);
        
        return $response;
    }
    
    /**
     * Fonction utilitaire pour convertir une chaîne en slug
     */
    private function slugify(string $text): string
    {
        // Convertir les caractères accentués en ASCII
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        
        // Convertir en minuscules
        $text = strtolower($text);
        
        // Supprimer tous les caractères qui ne sont pas des lettres, chiffres, tirets ou underscores
        $text = preg_replace('/[^a-z0-9\-_]/', '', $text);
        
        // Remplacer les espaces par des tirets
        $text = str_replace(' ', '-', $text);
        
        // Supprimer les tirets en début et fin
        $text = trim($text, '-');
        
        // Si vide, utiliser un placeholder
        if (empty($text)) {
            return 'document';
        }
        
        return $text;
    }
}
