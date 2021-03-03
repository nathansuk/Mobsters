<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Mission;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MissionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Mission::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Intitulé'),
            TextEditorField::new('description', 'Description'),
            TextField::new('type', 'Le Type'),
            AssociationField::new('clan', 'Associé au clan')

        ];
    }

}
