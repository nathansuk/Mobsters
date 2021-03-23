<?php

namespace App\Controller\Admin;

use App\Entity\Clan;
use App\Entity\Clans;
use App\Entity\Emprunt;
use App\Entity\Mission;
use App\Entity\News;
use App\Entity\NewsCategory;
use App\Entity\Transaction;
use App\Entity\User;
use App\Entity\UserMission;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Administration');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Accueil', 'fa fa-home');

        yield MenuItem::section('Le Times', 'fas fa-atlas');
        yield MenuItem::linkToCrud('Catégories', 'fas fa-list', NewsCategory::class);
        yield MenuItem::linkToCrud('Articles', 'far fa-newspaper', News::class);

        yield MenuItem::section('Communauté', 'fas fa-wrench');
        yield MenuItem::linkToCrud('Les Clans', 'fas fa-users', Clans::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);

        yield MenuItem::section('Missions', 'fas fa-clipboard');
        yield MenuItem::linkToCrud('Les missions', 'far fa-clipboard', Mission::class);
        yield MenuItem::linkToCrud('Mission des joueurs', 'fas fa-users', UserMission::class);

        yield MenuItem::section('Banque', 'fas fa-money-check-alt');
        yield MenuItem::linkToCrud('Emprunts', 'fas fa-file-invoice-dollar', Emprunt::class);
        yield MenuItem::linkToCrud('Transactions', 'fas fa-search-dollar', Transaction::class);

    }
}
