<?php


namespace App\Services\Bank;


use App\Entity\Transaction;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class TransactionService
{

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    /*
     * Add a new transaction
     */
    public function newTransaction(Transaction $transaction, User $sender, User $receiver, int $amount) {

        $transaction
            ->setSender($sender->getUsername())
            ->setAmount($amount)
            ->setReceiver($receiver->getUsername())
            ->setDate(new \DateTime("now"));

        $sender->setMoney($sender->getMoney() - $amount);
        $receiver->setMoney($receiver->getMoney() + $amount);

        $this->em->persist($sender);
        $this->em->persist($receiver);
        $this->em->persist($transaction);
        $this->em->flush();
    }

}