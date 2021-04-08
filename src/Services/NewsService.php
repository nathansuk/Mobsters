<?php


namespace App\Services;


use App\Entity\Comments;
use App\Entity\News;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class NewsService
{

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getAllNews(): array {
        return $this->em->getRepository(News::class)->findBy(array(), ['createdAt' => 'DESC']);
    }

    public function getNewsById(int $id): ?News {
        return $this->em->getRepository(News::class)->find($id);
    }

    /*
     * Post comment
     */
    public function postComment(Comments $comment, News $news, User $user): void {
        $comment
            ->setArticle($news)
            ->setUser($user)
            ->setDate(new \DateTime("now"));

        $this->em->persist($comment);
        $this->em->flush();
    }

}