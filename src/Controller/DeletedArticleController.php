<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
# controller to confirm this article has been deleted
class DeletedArticleController extends AbstractController
{
    #[Route('/deletedArticle', name: 'app_deleted_article')]
    public function index(): Response
    {
        return $this->render('deleted_article/index.html.twig', [
            'controller_name' => 'DeletedArticleController',
        ]);
    }
}
