<?php


namespace App\Services\Bank;


use App\Entity\Emprunt;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class EmpruntService
{

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getEmpruntById(int $id): ?Emprunt {
        return $this->em->getRepository(Emprunt::class)->find($id);
    }

    public function getPendingDemands(): array {
        return $this->em->getRepository(Emprunt::class)->findBy(['isAccepted' => false]);
    }

    public function getAcceptedDemands(): array {
        return $this->em->getRepository(Emprunt::class)->findBy([
            'isAccepted' => true,
            'isReimbursed' => false
        ]);
    }

    public function getReimbursedDemands(): array {
        return $this->em->getRepository(Emprunt::class)->findBy(['isReimbursed' => true]);
    }

    public function newEmprunt(Emprunt $emprunt, User $user, int $montant, string $motif): void {
        $emprunt
            ->setUser($user)
            ->setMontant($montant)
            ->setMotif($motif)
            ->setIsAccepted(false)
            ->setIsReimbursed(false)
            ->setInterets(0.0)
            ->setDate(new \DateTime("now"));

        $this->em->persist($emprunt);
        $this->em->flush();
    }

    /*
     * When a banker accept a demand
     */
    public function acceptDemand(Emprunt $emprunt, string $acceptedBy): void {

        $user = $emprunt->getUser();
        $emprunt
            ->setIsAccepted(true)
            ->setAcceptedBy($acceptedBy);

        $user->setMoney($user->getMoney() + $emprunt->getMontant());

        $this->em->persist($emprunt);
        $this->em->persist($user);
        $this->em->flush();
    }

    /*
     * Action : When a user has reimbursed his emprunt, this function set her as reimbursed true.
     */
    public function setEmpruntAsReimbursed(Emprunt $emprunt, string $validatedBy): void {

        $emprunt
            ->setIsReimbursed(true)
            ->setValidateBy($validatedBy);

        $this->em->persist($emprunt);
        $this->em->flush();

    }

}