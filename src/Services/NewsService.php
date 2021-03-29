<?php


namespace App\Services;


use App\Entity\News;
use Doctrine\ORM\EntityManagerInterface;

class NewsService
{

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getAllNews(): array {
        return $this->em->getRepository(News::class)->findAll();
    }

    public function getNewsById(int $id): News {

        return $this->em->getRepository(News::class)->find($id);

    }

}