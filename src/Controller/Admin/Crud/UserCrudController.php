<?php

namespace App\Controller\Admin\Crud;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('email', 'Adresse mail')->setPermission('ROLE_ADMIN'),
            TextField::new('username', 'Pseudo')->setPermission('ROLE_ADMIN'),
            IntegerField::new('money', 'Livres Sterling')->setPermission('ROLE_ADMIN'),
            ArrayField::new('roles', 'Ranks')->setPermission('ROLE_ADMIN'),
            AssociationField::new('clan', 'Clan')->setPermission('ROLE_ADMIN'),
            AssociationField::new('userMissions', 'Missions assignées')->setPermission('ROLE_ADMIN'),
            IntegerField::new("finishedMission")->setPermission('ROLE_ADMIN')
        ];
    }

}
