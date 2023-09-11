<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowArticleController extends AbstractController
{
    #[Route('/article/{slug}', name: 'show_article')]
    public function index(ArticleRepository $articleRepository,CommentRepository $commentRepository,string $slug,Request $request,EntityManagerInterface $entityManager): Response
    {
        $resultA = $articleRepository->findOneBy(['slug' => $slug]);
        $resultC = $commentRepository->findBy(
            ['article_id' => $resultA->getId()],
            ['date' => 'ASC']
        );
        $user = $this->getUser()->getUserIdentifier();
        $comment = new Comment();
        $comment->setArticleId($resultA);
        $comment->setAuthor($user);
        $y = (new \DateTime);
        $comment->setDate($y);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $entityManager->persist($comment);
            $entityManager->flush();
        }

        return $this->render('show_article/index.html.twig', [
            'resultA' => $resultA,
            'resultC' => $resultC,
            'form' => $form,
        ]);
    }
}
