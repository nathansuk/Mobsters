<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Clans;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ClansCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Clans::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('description'),
            TextField::new('badge'),
        ];
    }

}
