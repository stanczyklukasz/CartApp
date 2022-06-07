<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Repository;

use App\Cart\Domain\Cart;
use App\Cart\Domain\CartRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineCartRepository implements CartRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function save(Cart $cart, bool $flush = true): void
    {
        $this->entityManager->persist($cart);

        if ($flush) {
            $this->entityManager->flush();
        }
    }
}