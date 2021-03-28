<?php

namespace App\Controller\Bank;

use App\Entity\Emprunt;
use App\Entity\Transaction;
use App\Form\EmpruntType;
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
         * TRANSACTION MODULE
         * We create the Transaction form and handle the request.
         */
        $transaction = new Transaction();
        $sendMoneyForm = $this->createForm(TransactionType::class);
        $sendMoneyForm->handleRequest($request);

        if($sendMoneyForm->isSubmitted() && !$sendMoneyForm->isValid()) {
            $this->addFlash('error', "Le formulaire est invalide, veuillez réessayer.");
            return $this->redirectToRoute('my_bank_account');
        }

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
            /*
             * We check if the receiver exist in database, else we throw an error.
             */
            if(!$receiver){
                $this->addFlash('notice', "Cet utilisateur n'existe pas");
                return $this->redirectToRoute('my_bank_account');
            } else {
                $receiverMoney = $userService->getUserMoney($receiver);
            }
            /*
            * We get the amount of the transaction
            * And we check if the amount sent is less than the sender's money.
            */
            $amount = $sendMoneyForm->get('amount')->getData();
            /*
             * If the user dont have enough money
             */
            if($amount > $senderMoney) {
                $this->addFlash('notice', "Attention, vous essayez d'envoyer trop d'argent !");
                return $this->redirectToRoute("my_bank_account");
            } else {
                $transaction->setSender($username)
                    ->setAmount($amount)
                    ->setReceiver($sendMoneyForm->get('receiver')->getData());

                $newSenderMoney = $sender->setMoney($senderMoney - $amount);
                $newReceiverMoney = $receiver->setMoney($receiverMoney + $amount);

                $entityManager->persist($newSenderMoney);
                $entityManager->persist($newReceiverMoney);
                $entityManager->persist($transaction);
                $entityManager->flush();

                $this->addFlash('success', 'Le virement a bien été envoyé !');
                return $this->redirectToRoute('my_bank_account');
            }
        }

        /*
         * EMPRUNT MODULE
         * Here we get the information from the emprunt form
         */
        $emprunt = new Emprunt();
        $askEmpruntForm = $this->createForm(EmpruntType::class);
        $askEmpruntForm->handleRequest($request);

        if($askEmpruntForm->isSubmitted() && !$askEmpruntForm->isValid()){

            $this->addFlash('error', 'Le formulaire est invalide !');
            return $this->redirectToRoute('my_bank_account');

        }

        if($askEmpruntForm->isSubmitted() && $askEmpruntForm->isValid()) {

            $username = $this->getUser()->getUsername();
            $user = $userService->getUserByUsername($username);

            $montant = $askEmpruntForm->get('montant')->getData();
            $motif = $askEmpruntForm->get('motif')->getData();
            $emprunt->setUser($user)
                ->setMontant($montant)
                ->setMotif($motif)
                ->setIsAccepted(false)
                ->setIsReimbursed(false)
                //TO-DO: Le taux sera configurable par les administrateurs.
                ->setInterets(0.0);


            $entityManager->persist($emprunt);
            $entityManager->flush();

            $this->addFlash('success', 'Votre demande a bien été envoyée. Consultez cette page régulièrement pour suivre son état.');
            return $this->redirectToRoute('my_bank_account');

        }

        /*
         * We get last transactions details
         */

        $lastTransactions = $userService->getUserTransactions(3, $this->getUser());

        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
            'lastTransactions' => $lastTransactions,
            'sendMoneyForm' => $sendMoneyForm->createView(),
            'askEmpruntForm' => $askEmpruntForm->createView()
        ]);
    }
}
