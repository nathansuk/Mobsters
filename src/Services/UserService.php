<?php


namespace App\Services;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getUserByUsername(string $username): ?object
    {
        return $this->em->getRepository(User::class)->findOneBy(['username' => $username]);
    }

}