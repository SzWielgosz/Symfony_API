<?php

namespace App\Controller\Admin;

use App\Entity\Composer;
use App\Entity\RefreshToken;
use App\Entity\Symphony;
use App\Entity\Tag;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
            ->setController(ComposerCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
    return Dashboard::new()
        ->setTitle('App');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::section('Sympho'),
            MenuItem::linkToCrud('Users', 'fa fa-tags', User::class),
            MenuItem::linkToCrud('Composers', 'fa fa-tags', Composer::class),
            MenuItem::linkToCrud('Symphonies', 'fa fa-tags', Symphony::class),
            MenuItem::linkToCrud('Tags', 'fa fa-tags', Tag::class),
            MenuItem::linkToCrud('Refresh Tokens', 'fa fa-tags', RefreshToken::class),
        ];
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
