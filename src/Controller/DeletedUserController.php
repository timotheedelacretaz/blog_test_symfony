<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeletedUserController extends AbstractController
{
    #[Route('/deletedUser', name: 'app_deleted_user')]
    public function index(): Response
    {
        return $this->render('deleted_user/index.html.twig', [
            'controller_name' => 'DeletedUserController',
        ]);
    }
}
