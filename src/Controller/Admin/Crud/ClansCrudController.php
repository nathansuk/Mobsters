<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Clans;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
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
            TextField::new('name', 'Nom du Clan')->setPermission('ROLE_ADMIN'),
            TextField::new('description', 'Description')->setPermission('ROLE_ADMIN'),
            TextField::new('badge', 'Badge')->setPermission('ROLE_ADMIN'),
            AssociationField::new('missions')->setPermission('ROLE_ADMIN')
        ];
    }

}
