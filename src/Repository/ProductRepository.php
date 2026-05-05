<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findFiltered(?string $categorySlug, ?string $brandSlug, ?string $search, ?string $featured): array
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.mainCategory', 'c')
            ->leftJoin('p.brand', 'b')
            ->where('p.active = :active')
            ->setParameter('active', true)
            ->orderBy('p.sortOrder', 'ASC')
            ->addOrderBy('p.name', 'ASC');

        if ($categorySlug) {
            $qb->andWhere('c.slug = :cat')->setParameter('cat', $categorySlug);
        }

        if ($brandSlug) {
            $qb->andWhere('b.slug = :brand')->setParameter('brand', $brandSlug);
        }

        if ($search) {
            $qb->andWhere('p.name LIKE :search OR p.shortDescription LIKE :search OR p.internalCode LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        if ($featured) {
            $qb->andWhere('p.isFeatured = :featured')->setParameter('featured', true);
        }

        return $qb->getQuery()->getResult();
    }

    public function findRelated(Product $product, int $limit = 4): array
    {
        $results = $this->createQueryBuilder('p')
            ->where('p.active = :active')
            ->andWhere('p.id != :id')
            ->andWhere('p.mainCategory = :cat OR p.brand = :brand')
            ->setParameter('active', true)
            ->setParameter('id', $product->getId())
            ->setParameter('cat', $product->getMainCategory())
            ->setParameter('brand', $product->getBrand())
            ->setMaxResults($limit * 4)   // fetch more to shuffle
            ->getQuery()
            ->getResult();

        shuffle($results);

        return array_slice($results, 0, $limit);
    }

    public function findFeatured(int $limit = 8): array
    {
        return $this->findBy(['active' => true, 'isFeatured' => true], ['sortOrder' => 'ASC'], $limit);
    }
}
