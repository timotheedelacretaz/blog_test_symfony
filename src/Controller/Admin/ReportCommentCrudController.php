<?php

namespace App\Controller\Admin;

use App\Entity\ReportComment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

# CRUD created with easyadmin bundle manage reported comment for the admin dashboard
class ReportCommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ReportComment::class;
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
