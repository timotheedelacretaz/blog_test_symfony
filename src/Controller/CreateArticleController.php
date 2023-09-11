<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\CreateArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateArticleController extends AbstractController
{
    #[Route('/create/article', name: 'app_create_article')]
    public function index(Request $request,EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $article = new Article();
        $y = (new \DateTime);
        $article->setDate($y);
        $article->setUserId($user);
        $form = $this->createForm(CreateArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $article->setSlug($article->getTitle());
            $entityManager->persist($article);
            $entityManager->flush();
        }
        return $this->render('create_article/index.html.twig', [
            'form'=>$form,
        ]);
    }
}
