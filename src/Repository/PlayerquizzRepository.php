<?php

namespace App\Repository;

use App\Entity\Playerquizz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Playerquizz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Playerquizz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Playerquizz[]    findAll()
 * @method Playerquizz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerquizzRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playerquizz::class);
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
