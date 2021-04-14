<?php

namespace App\Repository;

use App\Entity\Playeranswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Playeranswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Playeranswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Playeranswer[]    findAll()
 * @method Playeranswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayeranswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playeranswer::class);
    }

    // /**
    //  * @return Playeranswer[] Returns an array of Playeranswer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Playeranswer
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
