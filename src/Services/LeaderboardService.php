<?php


namespace App\Services;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class LeaderboardService
{

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getRichestUsers(?int $max): array
    {
        return $this->em->getRepository(User::class)->findBy(array(), ['money' => 'DESC'], $max);
    }

    public function getBestMissionUser(?int $max): array
    {
        return $this->em->getRepository(User::class)->findBy(array(), ['finishedMission' => 'DESC'], $max);

    }


}