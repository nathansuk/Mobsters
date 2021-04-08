<?php


namespace App\Services\Missions;


use App\Entity\Mission;
use App\Entity\User;
use App\Entity\UserMission;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class MissionService
{

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    /*
     * Get all missions
     */
    public function getAvailableMission(): array {
         return $this->em->getRepository(Mission::class)->findAll();
    }

    /*
     * Get mission using his ID
     */
    public function getMissionById(int $id): ?Mission {
        return $this->em->getRepository(Mission::class)->find($id);
    }
    
    /*
     * Add mission to user
     */
    public function addUserMission(UserInterface $user, Mission $mission): void {

        $userMission = new UserMission();
        $userMission
            ->setUser($user)
            ->setMission($mission)
            ->setDone(false)
            ->setReward($mission->getReward())
            ->setIsRewarded(false)
            ->setWaitingConfirmation(false);

        $this->em->persist($userMission);
        $this->em->flush();
    }

    /*
     * Remove a mission from user
     */
    public function removeMission(User $user, UserMission $userMission): void {
        $user
            ->removeUserMission($userMission);
        $this->em->persist($user);
        $this->em->flush();
    }

    /*
     * Mark a mission as finished, and set her to "waiting confirmation"
     */
    public function markMissionAsFinished(UserMission $userMission): void {
        $userMission
            ->setWaitingConfirmation(true);
        $this->em->persist($userMission);
        $this->em->flush();
    }


}