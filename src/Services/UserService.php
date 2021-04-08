<?php


namespace App\Services;


use App\Entity\Transaction;
use App\Entity\User;
use App\Entity\UserMission;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getUserByUsername(string $username): ?User {
        return $this->em->getRepository(User::class)->findOneBy(['username' => $username]);
    }

    public function getUserById($id): array {
        return $this->em->getRepository(UserMission::class)->findBy(['user' => $id]);
    }

    /**
     * @param object $user
     * @param object $mission
     * @return bool
     * This function help to know if user already has the mission ($mission)
     * we check if usermission contains the user and the mission.
     * If this return null ( the mission hasnt been already accepted by user) then return false.
     */
    public function userAlreadyHasMission(object $user, object $mission): bool {
        $userMission = $this->em->getRepository(UserMission::class)->findBy(
            [
                'user' => $user,
                'mission' => $mission
            ]
        );
        if($userMission == null) {
            return false;
        } else {
            return true;
        }
    }

    public function getUserTransactions(int $max, object $user): array {
        return $this->em->getRepository(Transaction::class)->findBy(['sender' => $user->getUsername()]);
    }

    public function getUserMissionsById(User $user, int $id): ?UserMission {
        return $this->em->getRepository(UserMission::class)->findOneBy(
            [
                'user' => $user,
                'mission' => $id
            ]);
    }

    public function getUsersRewardedMission(User $user): array {
        return $this->em->getRepository(UserMission::class)->findBy([
            'user' => $user,
            'isRewarded' => true
        ]);
    }

}