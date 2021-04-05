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

    /*
     * New emprunt
     */
    public function newEmprunt(Emprunt $emprunt, User $user, int $montant, string $motif): void {
        $emprunt
            ->setUser($user)
            ->setMontant($montant)
            ->setMotif($motif)
            ->setIsAccepted(false)
            ->setIsReimbursed(false)
            //TO-DO: Le taux sera configurable par les administrateurs.
            ->setInterets(0.0)
            ->setDate(new \DateTime("now"));

        $this->em->persist($emprunt);
        $this->em->flush();
    }

}