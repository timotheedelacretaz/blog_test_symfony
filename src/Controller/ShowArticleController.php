<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\ReportArticle;
use App\Entity\ReportComment;
use App\Entity\Vote;
use App\Form\CommentType;
use App\Form\ReportArticleType;
use App\Form\VoteType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\ReportArticleRepository;
use App\Repository\ReportCommentRepository;
use App\Repository\UserRepository;
use App\Repository\VoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;

class ShowArticleController extends AbstractController
{
    #[Route('/article/{slug}', name: 'show_article')]
    public function index(ArticleRepository $articleRepository,ReportCommentRepository $reportCommentRepository,ReportArticleRepository $reportArticleRepository,CommentRepository $commentRepository,VoteRepository $voteRepository, string $slug,Request $request,EntityManagerInterface $entityManager): Response
    {
        $resultA = $articleRepository->findOneBy(['slug' => $slug]);

        $resultC = $commentRepository->findBy(
            ['article_id' => $resultA->getId()],
            ['date' => 'ASC']
        );
        $user = $this->getUser();
        $voteUser = $voteRepository->findIfUserVoted($user,$resultA);
        $reportArticleUser = $reportArticleRepository->findIfUserReportedArticle($user,$resultA);


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

            $reportArticle = new ReportArticle();
            $reportArticle->setUserId($user);
            $reportArticle->setArticleId($resultA);
            $formReportArticle = $this->createForm(ReportArticleType::class,$reportArticle);
            $formReportArticle->handleRequest($request);
            if ($formReportArticle->isSubmitted() && $formReportArticle->isValid()) {
                $reportArticle = $formReportArticle->getData();
                if ($reportArticleUser == null)
                {
                    $entityManager->persist($reportArticle);
                }
                $entityManager->flush();
                $numberReportArticle = $resultA->setReport(count($reportArticleRepository->findBy(['article_id' => $resultA->getId()])));
                $entityManager->persist($numberReportArticle);
                $entityManager->flush();
                return $this->redirect('/article/'.$resultA->getSlug().'');
            }




            $comment = new Comment();
            $comment->setArticleId($resultA);
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
            $formReportArticle = '';
        }







        return $this->render('show_article/index.html.twig', [
            'resultA' => $resultA,
            'resultC' => $resultC,
            'formComment' => $formComment,
            'formVote' => $formVote,
            'voteUser' => $voteUser,
            'formReportArticle' => $formReportArticle,
            'reportArticleUser' => $reportArticleUser,
        ]);
    }
    #[Route('/article/delete/{slug}',name: 'delete_article' ,methods: ['GET', 'DELETE'])]
    public function deleteArticle(EntityManagerInterface $entityManager, string $slug,ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->findOneBy(['slug' => $slug]);
        if ($this->getUser() == $article)
        {
            $entityManager->remove($article);
            $entityManager->flush();
            return $this->redirect('/deletedArticle');
        }
        else {
            return $this->redirect('/');
        }

    }



    #[Route('/comment/delete/{slug}/{slug2}',name: 'delete_comment' ,methods: ['GET', 'DELETE'])]
    public function deleteComment(EntityManagerInterface $entityManager,string $slug, string $slug2,ArticleRepository $articleRepository,CommentRepository $commentRepository): Response
    {
        $comment = $commentRepository->findOneBy(['id' => $slug]);
        if ($this->getUser() == $comment)
        {
            $entityManager->remove($comment);
            $entityManager->flush();
            return $this->redirect('/deletedArticle');
        }
        else {
            return $this->redirect('/article/'.$slug2.'');
        }
    }

    #[Route('/comment/report/{slug}/{slug2}',name: 'report_comment' ,methods: ['GET'])]
    public function reportComment(EntityManagerInterface $entityManager,ReportCommentRepository $reportCommentRepository,string $slug, string $slug2,CommentRepository $commentRepository): Response
    {
        $user = $this->getUser();
        $comment = $commentRepository->findOneBy(['id' => $slug]);
        $reportCommentUser = $reportCommentRepository->findIfUserReportedComment($user,$comment);
        $report = new ReportComment();
        $report->setCommentId($comment);
        $report->setUserId($user);
        if ($reportCommentUser == null){
            $entityManager->persist($report);
        }
        $entityManager->flush();
        $numberReportArticle = $comment->setReport(count($reportCommentRepository->findBy(['comment_id' => $comment->getId()])));
        $entityManager->persist($numberReportArticle);
        $entityManager->flush();
        return $this->redirect('/article/'.$slug2.'');
    }


    #[Route('/article/pdf/{slug}',name: 'pdf' ,methods: ['GET'])]
    public function pdf(string $slug,ArticleRepository $articleRepository): Response
    {
        $resultA = $articleRepository->findOneBy(['slug' => $slug]);
        $html =  $this->renderView('pdf/index.html.twig', [
            'resultA' => $resultA,
        ]);
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('enable_css_float', false);
        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->loadHtml($html);

        $dompdf->render();
        $dompdf->stream('resume', ["Attachment" => false]);
        exit();

    }


}
