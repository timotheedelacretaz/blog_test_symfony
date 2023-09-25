<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Vote;
use App\Form\CommentType;
use App\Form\VoteType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use App\Repository\VoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowArticleController extends AbstractController
{
    #[Route('/article/{slug}', name: 'show_article')]
    public function index(ArticleRepository $articleRepository,CommentRepository $commentRepository,VoteRepository $voteRepository, string $slug,Request $request,EntityManagerInterface $entityManager): Response
    {
        $resultA = $articleRepository->findOneBy(['slug' => $slug]);

        $resultC = $commentRepository->findBy(
            ['article_id' => $resultA->getId()],
            ['date' => 'ASC']
        );
        $user = $this->getUser();
        $voteUser = $voteRepository->findIfUserVoted($user,$resultA);



        if ($this->isGranted('ROLE_USER')){
            $vote = new Vote();
            $vote->setUserId($user);
            $vote->setArticleId($resultA);
            $formVote = $this->createForm(VoteType::class,$vote);
            $formVote->handleRequest($request);
            if ($formVote->isSubmitted() && $formVote->isValid()) {
                $vote = $formVote->getData();
                if ($voteUser != null)
                {
                    $entityManager->remove($voteUser);
                }
                else {
                    $entityManager->persist($vote);
                }
                $entityManager->flush();
                $numberUpvote = $resultA->setUpvote(count($voteRepository->findBy(['article_id' => $resultA->getId()])));
                $entityManager->persist($numberUpvote);
                $entityManager->flush();
                return $this->redirect('/article/'.$resultA->getSlug().'');
            }
            $comment = new Comment();
            $comment->setArticleId($resultA);
            $comment->setAuthor($user->getUserIdentifier());
            $comment->setUserId($user);
            $y = (new \DateTime);
            $comment->setDate($y);
            $formComment = $this->createForm(CommentType::class, $comment);
            $formComment->handleRequest($request);
            if ($formComment->isSubmitted() && $formComment->isValid()) {
                $comment = $formComment->getData();
                $entityManager->persist($comment);
                $entityManager->flush();
                return $this->redirect('/article/'.$resultA->getSlug().'');
            }
        }
        else {
            $formComment = 'You have to be logged to write comment';
            $formVote = '';
        }



        return $this->render('show_article/index.html.twig', [
            'resultA' => $resultA,
            'resultC' => $resultC,
            'formComment' => $formComment,
            'formVote' => $formVote,
            'voteUser' => $voteUser,
        ]);
    }
}
