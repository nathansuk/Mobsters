<?php


namespace App\Services\Missions;


use App\Entity\Mission;
use Doctrine\ORM\EntityManagerInterface;

class MissionService
{

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getAvailableMission(): array {
        return $this->em->getRepository(Mission::class)->findAll();
    }

    public function getMissionById(int $id): ?object {
        return $this->em->getRepository(Mission::class)->find($id);
    }


}