<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

# controller to show all article made
class ReadArticleController extends AbstractController
{
    #[Route('/read/article', name: 'app_read_article')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $result = $articleRepository->findBy(
            [],
            ['date' => 'DESC']
        );

        return $this->render('read_article/index.html.twig',[
            'result' => $result,
        ]);
    }
}
