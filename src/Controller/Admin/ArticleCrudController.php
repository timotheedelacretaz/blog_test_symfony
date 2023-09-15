<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextEditorField::new('content'),
            SlugField::new('slug')->setTargetFieldName('title'),
            DateTimeField::new('date')->hideOnForm(),
            IdField::new('user_id_id')
                ->hideOnIndex()
                ->hideWhenCreating(),
        ];
    }

    /**
     * @param $article Article
     */
    public function persistEntity(EntityManagerInterface $entityManager, $article): void
    {
        $article->setdate(new \DateTime);
        $article->setUserId($this->getUser());
        parent::persistEntity($entityManager, $article);
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::EDIT)
            ;
    }

}
