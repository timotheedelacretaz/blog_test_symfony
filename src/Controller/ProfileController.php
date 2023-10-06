<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\DeleteUserType;
use App\Form\ProfileType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

# controller for the user profile find all article and comment made by the user
# use a form to modify the description
class ProfileController extends AbstractController
{
    #[Route('/profile/{slug}', name: 'app_profile')]
    public function index(string $slug,Session $session,EntityManagerInterface $entityManager,UserRepository $userRepository,ArticleRepository $articleRepository,CommentRepository $commentRepository,Request $request): Response
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

        $formDeleteUser = $this->createForm(DeleteUserType::class,$userMod);
        $formDeleteUser->handleRequest($request);
        if ($formDeleteUser->isSubmitted() && $formDeleteUser->isValid()) {
            $userMod = $formDeleteUser->getData();
            $entityManager->remove($userMod);
            $entityManager->flush();
            $session = $request->getSession()->getName();
            $request->getSession()->remove($session);
            $session = new Session();
            $session->invalidate();
            return $this->redirect('/deletedUser');
        }


        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'article' => $article,
            'comment' => $comment,
            'formUser' => $formUser,
            'formDeleteUser' => $formDeleteUser,
            'verify' => $slug
        ]);
    }
}
