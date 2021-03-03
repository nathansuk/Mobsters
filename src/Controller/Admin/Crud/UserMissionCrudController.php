<?php

namespace App\Controller\Admin\Crud;

use App\Entity\UserMission;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class UserMissionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserMission::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user', 'Utilisateur'),
            AssociationField::new('mission', 'Mission'),
            BooleanField::new('done', 'Réalisée')
        ];
    }

}
