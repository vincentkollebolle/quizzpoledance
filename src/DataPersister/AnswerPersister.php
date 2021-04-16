<?php
namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Answer;
use Doctrine\ORM\EntityManagerInterface;

class AnswerPersister implements DataPersisterInterface
{
  protected $em;

  public function __construct(EntityManagerInterface $em){
    $this->em = $em;
  }

  public function supports($data): bool {
    return $data instanceof Answer;
  }

  public function persist($data){
    $this->em->persist($data);
    $this->em->flush();
  }

  public function remove($data){
    $this->em->remove($data);
    $this->em->flush();
  }
}