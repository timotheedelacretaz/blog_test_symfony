<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ArticleRepository $articleRepository,UserRepository $userRepository): Response
    {
        $result = $articleRepository->findAllNotRecommendedArticle();

        $recommended = $articleRepository->findAllRecommendedArticle();


        return $this->render('home/index.html.twig',[
            'result' => $result,
            'recommended' => $recommended,
        ]);

    }
}
