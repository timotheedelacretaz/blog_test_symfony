<?php

namespace App\Controller\Admin;


use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('author'),
            TextEditorField::new('content'),
            DateTimeField::new('date'),
        ];
    }
    /**
     * @param $comment Comment
     */
    /*public function persistEntity(EntityManagerInterface $entityManager, $comment): void
    {
        $comment->setArticleId;
        parent::persistEntity($entityManager, $comment);
    }*/

}
