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
            TextField::new('email', 'Adresse mail'),
            TextField::new('username', 'Pseudo'),
            IntegerField::new('money', 'Livres Sterling'),
            ArrayField::new('roles', 'Ranks'),
            AssociationField::new('clan', 'Clan'),
            AssociationField::new('userMissions', 'Missions assignées')
        ];
    }

}
