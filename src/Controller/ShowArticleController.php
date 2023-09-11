<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowArticleController extends AbstractController
{
    #[Route('/article/{slug}', name: 'show_article')]
    public function index(ArticleRepository $articleRepository,string $slug,Request $request): Response
    {
        $result = $articleRepository->findOneBy(['slug' => $slug]);
        $x = $articleRepository->findOneBy(['slug' => $slug])->getId();
        $user = $this->getUser();
        $comment = new Comment();
        $comment->setArticleId($x);
        $comment->setDate(new \DateTime());
        $comment->setAuthor($user.email);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        /*if ($form->isSubmitted() && $form->isValid()) {

        }*/

        return $this->render('show_article/index.html.twig', [
            'result' => $result,
            'form' => $form,
        ]);
    }
}
