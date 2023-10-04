<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\ReportArticle;
use App\Entity\ReportComment;
use App\Entity\User;
use App\Entity\Vote;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\ReportArticleRepository;
use App\Repository\ReportCommentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        private CommentRepository $commentRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $id = $this->getUser();
        $user = $this->userRepository->findOneBy(['id' => $id]);
        if ($user->getDateVisitedAdmin() == null)
            {
                $d = new \DateTime();
            }
        else {
            $d =  $user->getDateVisitedAdmin();
        }
        $a = $this->articleRepository->findAllGreaterThanDate($d);
        $u = $this->articleRepository->findAllGreaterThanDate($d);

        $date = new \DateTime();
        $user->setDateVisitedAdmin($date);
        $this->entityManager->persist($user);
        $this->entityManager->flush();


        $format = 'm-Y';
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
        $rA = $this->articleRepository->findAllReported();
        $rC = $this->commentRepository->findAllReported();


        return $this->render('admin_dashboard/index.html.twig',[
            'chart' => $chart,
            'chart2' => $chart2,
            'a' => $a,
            'u' => $u,
            'rA' => $rA,
            'rC' => $rC,
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
        yield MenuItem::linkToCrud('Report Article', 'fas fa-list', ReportArticle::class)
            ->setPermission('ROLE_SUPER_ADMIN');
        yield MenuItem::linkToCrud('Report Comment', 'fas fa-list', ReportComment::class)
            ->setPermission('ROLE_SUPER_ADMIN');
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

    #[Route('/admin/comment/delete/{slug}',name: 'delete_admin_comment' ,methods: ['GET', 'DELETE'])]
    public function deleteAdminComment(EntityManagerInterface $entityManager,string $slug,CommentRepository $commentRepository): Response
    {
        $comment = $commentRepository->findOneBy(['id' => $slug]);
        $entityManager->remove($comment);
        $entityManager->flush();
        return $this->redirect('http://localhost:8000/admin');
    }
    #[Route('/admin/article/delete/{slug}',name: 'delete_admin_article' ,methods: ['GET', 'DELETE'])]
    public function deleteAdminArticle(EntityManagerInterface $entityManager, string $slug,ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->findOneBy(['slug' => $slug]);
        $entityManager->remove($article);
        $entityManager->flush();
        return $this->redirect('http://localhost:8000/admin');
    }


}
