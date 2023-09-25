<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile/{slug}', name: 'app_profile')]
    public function index(string $slug,EntityManagerInterface $entityManager,UserRepository $userRepository,ArticleRepository $articleRepository,CommentRepository $commentRepository,Request $request): Response
    {
        $user = $userRepository->findOneBy(['id' => $slug]);

        $article = $articleRepository->findAllArticleById($user->getId());
        $comment = $commentRepository->findAllCommentById($user->getId());
        $userMod = $user;
        $formUser = $this->createForm(ProfileType::class,$userMod);
        $formUser->handleRequest($request);
        if ($formUser->isSubmitted() && $formUser->isValid()) {
            $userMod = $formUser->getData();
            $entityManager->persist($userMod);
            $entityManager->flush();
            return $this->redirect('/profile/'.$slug.'');
        }
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'article' => $article,
            'comment' => $comment,
            'formUser' => $formUser,
            'verify' => $slug
        ]);
    }
}
