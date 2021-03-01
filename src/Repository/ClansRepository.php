<?php

namespace App\Repository;

use App\Entity\Clans;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Clans|null find($id, $lockMode = null, $lockVersion = null)
 * @method Clans|null findOneBy(array $criteria, array $orderBy = null)
 * @method Clans[]    findAll()
 * @method Clans[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClansRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Clans::class);
    }

    // /**
    //  * @return Clans[] Returns an array of Clans objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Clans
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
