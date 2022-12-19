<?php

namespace App\Controller\Admin;

use App\Entity\Works;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class WorksCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Works::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextField::new('description'),
            AssociationField::new('categories')->renderAsNativeWidget(),
            ImageField::new('filesOrlinks')->setUploadDir('public/uploads')
        ];
    }

}
