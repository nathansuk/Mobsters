<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Clan;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ClanCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Clan::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('name'),
            TextField::new('description'),
            TextField::new('badge')
        ];
    }

}
