<?php

namespace App\Controller\Admin\Crud;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Liste des utilisateurs')
            ->setEntityLabelInSingular(fn (?User $user) => $user ? $user->getUsername() : 'Utilisateur inconnu')
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('roles', 'Ranks'))
            ->add(EntityFilter::new('clan', 'Clan'))
            ;
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
            IntegerField::new("finishedMission", 'Missions finies')->setPermission('ROLE_ADMIN')
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setLabel('Supprimer');
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setLabel('Modifier');
            })
            ->disable(Action::SAVE_AND_ADD_ANOTHER, Action::NEW);

    }

}
