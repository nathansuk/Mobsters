<?php

namespace App\Controller\Admin\Crud;

use App\Entity\User;
use App\Entity\UserMission;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use JetBrains\PhpStorm\Pure;

class UserMissionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserMission::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('user', 'Utilisateur'))
            ->add(BooleanFilter::new('done', 'Finie'))
            ->add(BooleanFilter::new('isRewarded', 'Récompensée'))
            ->add(BooleanFilter::new('waitingConfirmation', 'En attente de confirmation'))
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user', 'Utilisateur'),
            AssociationField::new('mission', 'Mission'),
            IntegerField::new('reward', 'Récompense'),
            BooleanField::new('waitingConfirmation', 'Attends confirmation'),
            BooleanField::new('done', 'Réalisée'),
            BooleanField::new('isRewarded', 'Récompensée'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        // this action executes the 'renderInvoice()' method of the current CRUD controller
        $giveReward = Action::new('giveReward',
            'Envoyer récompense',
            'fas fa-medal')
            ->linkToCrudAction('giveReward')
            ->displayIf(fn ($entity) => !$entity->getIsRewarded());


        return $actions
            ->add(Crud::PAGE_INDEX, $giveReward)
            ->disable(Action::NEW)
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setLabel('Supprimer');
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setLabel('Modifier');
            });
    }



    #[Pure] public function giveReward(AdminContext $context)
    {
        $userMissionId = $context->getRequest()->query->get('entityId');

        $userMission = $this->getDoctrine()->getRepository(UserMission::class)->find($userMissionId);

        $user = $this->getDoctrine()->getRepository(User::class)->find($userMission->getUser()->getId());

        $userMission
            ->setIsRewarded(true)
            ->setDone(true)
            ->setWaitingConfirmation(false);
        $user
            ->setMoney($user->getMoney() + $userMission->getReward())
            ->setFinishedMission($user->getFinishedMission() + 1);

        $this->persistEntity($this->get('doctrine')->getManagerForClass($context->getEntity()->getFqcn()), $user);
        $this->persistEntity($this->get('doctrine')->getManagerForClass($context->getEntity()->getFqcn()), $userMission);
        $this->addFlash('success', 'La récompense a été envoyée !');

        return $this->redirect($this->get(CrudUrlGenerator::class)->build()->setAction(Action::INDEX)->generateUrl());

    }

}
