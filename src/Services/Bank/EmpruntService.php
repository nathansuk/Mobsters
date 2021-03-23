<?php


namespace App\Services\Bank;


use App\Entity\Emprunt;
use Doctrine\ORM\EntityManagerInterface;

class EmpruntService
{

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getEmpruntById(int $id): ?Emprunt {

        return $this->em->getRepository(Emprunt::class)->find($id);

    }

}