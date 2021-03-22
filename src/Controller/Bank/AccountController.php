<?php

namespace App\Controller\Bank;

use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{

    /**
     * @param UserService $userService
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/banque", name="my_bank_account")
     */
    public function index(UserService $userService, Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute("home");
        }

        /*
         * We create the form and handle the request.
         */
        $transaction = new Transaction();
        $sendMoneyForm = $this->createForm(TransactionType::class);
        $sendMoneyForm->handleRequest($request);

        if($sendMoneyForm->isSubmitted() && $sendMoneyForm->isValid()) {

            /*
            * We get the sender User object
            */
            $username = $this->getUser()->getUsername();
            $sender = $userService->getUserByUsername($username);
            $senderMoney = $userService->getUserMoney($sender);

            /**
             * We get the receiver User object
             */
            $receiver = $userService->getUserByUsername($sendMoneyForm->get('receiver')->getData());
            if(!$receiver){
                $this->addFlash('notice', "Cte utilisateur n'existe pas");
                return $this->redirectToRoute('my_bank_account');
            }
            $receiverMoney = $userService->getUserMoney($receiver);

            /*
            * We get the amount of the transaction
            * And we check if the amount sent is less than the sender's money.
            */
            $amount = $sendMoneyForm->get('amount')->getData();

            if($amount > $senderMoney) {
                $this->addFlash('notice', "Attention, vous essayez d'envoyer trop d'argent !");
                return $this->redirectToRoute("my_bank_account");
            }

            /*
             * We check if the receiver exist in database, else we throw an error.
             */

            $transaction->setSender($username)
                ->setAmount($amount)
                ->setReceiver($sendMoneyForm->get('receiver')->getData());

            $newSenderMoney = $sender->setMoney($senderMoney - $amount);
            $newReceiverMoney = $receiver->setMoney($receiverMoney + $amount);

            $entityManager->persist($newSenderMoney);
            $entityManager->persist($newReceiverMoney);
            $entityManager->persist($transaction);
            $entityManager->flush();

            return $this->redirectToRoute('my_bank_account');
        }


        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
            'sendMoneyForm' => $sendMoneyForm->createView()
        ]);
    }
}
