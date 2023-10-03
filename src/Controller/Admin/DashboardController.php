<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Vote;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DashboardController extends AbstractDashboardController
{

    public function __construct(
        private ChartBuilderInterface $chartBuilder,
        private UserRepository $userRepository,
        private ArticleRepository $articleRepository,
    ) {
    }
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $format = 'm';
        $r = $this->userRepository->findUserBetweenDate('2023-01-01 01:01:01','2023-12-30 01:01:01' );
        $labels = [];
        $data = [];

        foreach ($r as $x){
            if (in_array($x->getDate()->format($format),$labels)){
                $data[array_search($x->getDate()->format($format),$labels)]+=1;

            }else{
                $labels[] = $x->getDate()->format($format);
                $data[] = 1;
            }

        }
        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => "User",
                    'backgroundColor' => 'rgb(255, 180, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $data,
                ],
            ],
        ]);

        $chart->setOptions([
            'responsive' =>'true',
            'scales' => [

                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => max($data),
                    'grid' => [
                        'color' => 'rgb(0, 0, 0)'
                    ]
                ],
                'x' => [
                    'grid' => [
                        'color' => 'rgb(0, 0, 0)'
                    ]
                ],
            ],
        ]);


        $r2 = $this->articleRepository->findArticleBetweenDate('2023-01-01 01:01:01','2023-12-30 01:01:01' );
        $labels2 = [];
        $data2 = [];

        foreach ($r2 as $x){
            if (in_array($x->getDate()->format($format),$labels2)){
                $data2[array_search($x->getDate()->format($format),$labels2)]+=1;

            }else{
                $labels2[] = $x->getDate()->format($format);
                $data2[] = 1;
            }

        }
        $chart2 = $this->chartBuilder->createChart(Chart::TYPE_LINE);
        $chart2->setData([
            'labels' => $labels2,
            'datasets' => [
                [
                    'label' => "Article",
                    'backgroundColor' => 'rgb(255, 180, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $data2,
                ],
            ],
        ]);

        $chart2->setOptions([
            'responsive' =>'true',
            'scales' => [

                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => max($data2),
                    'grid' => [
                        'color' => 'rgb(0, 0, 0)'
                    ]
                ],
                'x' => [
                    'grid' => [
                        'color' => 'rgb(0, 0, 0)'
                    ]
                ],
            ],
        ]);

        return $this->render('admin_dashboard/index.html.twig',[
            'chart' => $chart,
            'chart2' => $chart2,
        ]);


    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Blog Dashboard');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Home', 'fas fa-dashboard', 'app_home');
        yield MenuItem::linkToRoute('Admin Home', 'fas fa-dashboard', 'admin');
        yield MenuItem::linkToCrud('Article', 'fas fa-list', Article::class);
        yield MenuItem::linkToCrud('Comment', 'fas fa-list', Comment::class);
        yield MenuItem::linkToCrud('Vote', 'fas fa-list', Vote::class)
            ->setPermission('ROLE_SUPER_ADMIN');
        yield MenuItem::linkToCrud('User', 'fas fa-list', User::class)
            ->setPermission('ROLE_SUPER_ADMIN');
    }

    public function configureAssets(): Assets
    {


        return parent::configureAssets()
            ->addWebpackEncoreEntry('app');
    }


}
