<?php

namespace App\Controller\Admin;

use App\Entity\Parcours;
use App\Entity\Etapes;
use App\Entity\Rendus;
use App\Entity\Ressource;
use App\Entity\User;
use App\Controller\Admin\UserCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        // Redirection vers la page utilisateur
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Examen Symfony');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Parcours', 'fa fa-map', Parcours::class);
        yield MenuItem::linkToCrud('Étapes', 'fa fa-list', Etapes::class);
        yield MenuItem::linkToCrud('Ressources', 'fa fa-book', Ressource::class);
        // yield MenuItem::linkToCrud('Rendus', 'fa fa-file', Rendus::class);
        // yield MenuItem::linkToCrud('Messages', 'fa fa-envelope', Message::class);
    }
}
