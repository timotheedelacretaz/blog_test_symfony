<?php

namespace App\Controller\Admin;

use App\Entity\ReportArticle;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
# CRUD created with easyadmin bundle manage reported article for the admin dashboard
class ReportArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ReportArticle::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
