<?php

namespace App\Repository;

use App\Entity\Representative;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RepresentativeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Representative::class);
    }

    public function findGroupedByState(): array
    {
        $reps = $this->findBy(['active' => true], ['state' => 'ASC']);
        $grouped = [];
        foreach ($reps as $rep) {
            $grouped[$rep->getState()][] = $rep;
        }
        return $grouped;
    }
}
