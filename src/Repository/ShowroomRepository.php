<?php

namespace App\Repository;

use App\Entity\Showroom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ShowroomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Showroom::class);
    }

    public function findByState(string $state): array
    {
        return $this->findBy(['active' => true, 'state' => $state], ['sortOrder' => 'ASC']);
    }
}
