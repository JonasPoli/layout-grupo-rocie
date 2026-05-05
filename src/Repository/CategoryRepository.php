<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findRoots(): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.parent IS NULL')
            ->andWhere('c.active = :active')
            ->setParameter('active', true)
            ->orderBy('c.sortOrder', 'ASC')
            ->getQuery()->getResult();
    }
}
