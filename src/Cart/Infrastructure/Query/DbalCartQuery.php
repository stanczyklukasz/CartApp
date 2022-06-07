<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Query;

use App\Cart\Application\Dto\CartSummary;
use App\Cart\Application\Query\CartQuery;
use App\Cart\Domain\Cart;
use Doctrine\DBAL\Connection;


class DbalCartQuery implements CartQuery
{
    public function __construct(
        private Connection $connection
    ) {
    }

    public function getCartSummary(string $uuid): ?CartSummary
    {
        $qb = $this->connection->createQueryBuilder();

        $cart = $qb
            ->select('c.id')
            ->addSelect('c.items')
            ->from('cart', 'c')
            ->where(
                $qb->expr()->eq('c.id', ':uuid')
            )
            ->setParameter('uuid', $uuid)
            ->executeQuery()
            ->fetchAssociative()
        ;

        if (empty($cart)) {
            return null;
        }

        return CartSummary::fromDbalResponse($cart);
    }
}