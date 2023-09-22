<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile/{slug}', name: 'app_profile')]
    public function index(string $slug,UserRepository $userRepository,ArticleRepository $articleRepository,CommentRepository $commentRepository): Response
    {
        $user = $userRepository->findOneBy(['id' => $slug]);
        $article = $articleRepository->findAllArticleById($user->getId());
        $comment = $commentRepository->findAllCommentById($user->getId());
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'article' => $article,
            'comment' => $comment
        ]);
    }
}
