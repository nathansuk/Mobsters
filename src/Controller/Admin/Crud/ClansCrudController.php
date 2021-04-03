<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Clans;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
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
            AssociationField::new('missions')->setPermission('ROLE_ADMIN')->onlyOnIndex()
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setLabel('Ajouter un clan');
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setLabel('Supprimer');
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setLabel('Modifier');
            });
    }

}
