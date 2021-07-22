<?php

namespace App\Repository;

use App\Entity\Quizz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Quizz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quizz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quizz[]    findAll()
 * @method Quizz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizzRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quizz::class);
    }

    public function findAll()
    {
        return $this->findBy(array(), array('score' => 'DESC'));
    }


    public function playernameAvailable($playername) {
        return $this->createQueryBuilder('q')
                ->andWhere('q.playername = :playername')
                ->setParameter('playername', $playername)
                ->getQuery()
                ->getResult() == null;
    }
}
