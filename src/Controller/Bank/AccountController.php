<?php

namespace App\Controller\Bank;

use App\Entity\Emprunt;
use App\Entity\Transaction;
use App\Form\EmpruntType;
use App\Form\TransactionType;
use App\Services\Bank\EmpruntService;
use App\Services\Bank\TransactionService;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{

    /**
     * @param UserService $userService
     * @param Request $request
     * @param TransactionService $transactionService
     * @param EmpruntService $empruntService
     * @return Response
     * @Route("/banque", name="my_bank_account")
     */
    public function index(
        UserService $userService,
        Request $request,
        TransactionService $transactionService,
        EmpruntService $empruntService): Response
    {
        if(!$this->getUser()) {
            return $this->redirectToRoute("app_login");
        }

        $username = $this->getUser()->getUsername();
        $user = $userService->getUserByUsername($username);
        $lastTransactions = $userService->getUserTransactions(3, $this->getUser());

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

            $receiver = $userService->getUserByUsername($sendMoneyForm->get('receiver')->getData());
            $amount = $sendMoneyForm->get('amount')->getData();

            if(!$receiver || $receiver === $user){
                $this->addFlash('error', "Oops, vous ne pouvez pas envoyer d'argent à cet utilisateur");
                return $this->redirectToRoute('my_bank_account');
            }

            if($amount > $user->getMoney() || $amount <= 0) {
                $this->addFlash('error', "Attention, le montant indiqué est trop élevé ou est incorrect");
                return $this->redirectToRoute("my_bank_account");
            }

            $transactionService->newTransaction($transaction, $user, $receiver, $amount);
            $this->addFlash('success', 'Le virement a bien été envoyé !');
            return $this->redirectToRoute('my_bank_account');
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

            $montant = $askEmpruntForm->get('montant')->getData();
            $motif = $askEmpruntForm->get('motif')->getData();

            if($montant <= 0){
                $this->addFlash('error', 'Le montant que vous souhaité emprunter est invalide');
                return $this->redirectToRoute('my_bank_account');
            }

            $empruntService->newEmprunt($emprunt, $user, $montant, $motif);
            $this->addFlash('success', 'Votre demande a bien été envoyée. Consultez cette page régulièrement pour suivre son état.');
            return $this->redirectToRoute('my_bank_account');

        }

        return $this->render('account/index.html.twig', [
            'controller_name' => 'Compte en banque',
            'lastTransactions' => $lastTransactions,
            'sendMoneyForm' => $sendMoneyForm->createView(),
            'askEmpruntForm' => $askEmpruntForm->createView()
        ]);
    }
}
