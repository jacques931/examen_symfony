<?php

namespace App\Controller;

use App\Entity\Parcours;
use App\Repository\EtapesRepository;
use App\Repository\ParcoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/parcours')]
class ParcoursController extends AbstractController
{
    private ParcoursRepository $parcoursRepository;
    private EtapesRepository $etapesRepository;

    public function __construct(ParcoursRepository $parcoursRepository, EtapesRepository $etapesRepository)
    {
        $this->parcoursRepository = $parcoursRepository;
        $this->etapesRepository = $etapesRepository;
    }
    
    #[Route('/', name: 'app_parcours_list', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(): Response
    {
        $user = $this->getUser();
        $parcours = [];
        
        if ($user) {
            // Si l'utilisateur est connecté, récupérer son parcours
            $parcours = $user->getParcours() ? [$user->getParcours()] : [];
        }
        
        return $this->render('parcours/index.html.twig', [
            'parcours' => $parcours,
        ]);
    }

    #[Route('/{id}', name: 'app_parcours_show', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function show($id): Response
    {
        $user = $this->getUser();
        $parcours = $this->parcoursRepository->find($id);
        
        if (!$parcours) {
            throw new NotFoundHttpException('Parcours non trouvé');
        }
        
        // Vérifier si l'utilisateur a accès à ce parcours
        $userParcours = $user->getParcours();
        if ($userParcours !== $parcours && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à ce parcours');
        }
        
        // Récupérer les étapes du parcours, triées par position
        $etapes = $this->etapesRepository->findBy(['parcours' => $parcours], ['position' => 'ASC']);
        
        return $this->render('parcours/show.html.twig', [
            'parcours' => $parcours,
            'etapes' => $etapes,
        ]);
    }
}
