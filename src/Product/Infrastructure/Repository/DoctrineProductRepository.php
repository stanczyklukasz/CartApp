<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Repository;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Entity\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineProductRepository implements ProductRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function isProductWithTitleExists(string $title): bool
    {
        $qb = $this->entityManager->createQueryBuilder();

        $quantity = $qb
            ->select(
                $qb->expr()->count('p.id')
            )
            ->where(
                $qb->expr()->eq('p.title', ':title')
            )
            ->from(Product::class, 'p')
            ->setParameter('title', $title)
            ->getQuery()
            ->getSingleScalarResult();

        return ($quantity > 0);
    }

    public function save(Product $product, bool $flush = true): void
    {
        $this->entityManager->persist($product);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function findById(int $id): ?Product
    {
        return $this->entityManager->getRepository(Product::class)->find($id);
    }

    public function remove(Product $product, bool $flush = true): void
    {
        $this->entityManager->remove($product);

        if ($flush) {
            $this->entityManager->flush();
        }
    }
}