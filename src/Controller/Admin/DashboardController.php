<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Entity\User;
use App\Entity\Works;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }
        if ('ROLE_USER' === $this->getUser()->getRoles()[0]) {
            return $this->redirect($adminUrlGenerator->setController(WorksCrudController::class)->generateUrl());
        }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');

        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
        ->setTitle('Administration Dépôt');
    }

    public function configureMenuItems(): iterable
    {
        //yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);

/*        if ('ROLE_USER' === $this->getUser()->getRoles()[0]) {
            return [
                MenuItem::linkToCrud('Works', 'fa fa-briefcase', Works::class),
            ];
        }*/

        return [
            MenuItem::linkToDashboard('Home', 'fa fa-home'),
            //MenuItem::linkToExitImpersonation('Back in Website', 'fa fa-backward'),
            MenuItem::linkToLogout('Logout', 'fa fa-backward'),

            MenuItem::section('Users'),
            MenuItem::linkToCrud('Users', 'fa fa-users', User::class),

            MenuItem::section('Categories'),
            MenuItem::linkToCrud('Categories', 'fa fa-tags', Categorie::class),

            MenuItem::section('Works'),
            MenuItem::linkToCrud('Works', 'fa fa-briefcase', Works::class),
        ];
    }
}
