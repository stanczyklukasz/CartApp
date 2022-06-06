<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Query;

use App\Product\Application\Dto\Product;
use App\Product\Application\Query\ProductQuery;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Tools\Pagination\Paginator;

class DbalProductQuery implements ProductQuery
{
    private const MAX_RESULTS = 3;

    public function __construct(
        private Connection $connection
    ) {
    }

    public function getPaginatedList(int $page): array
    {
        $qb = $this->connection->createQueryBuilder();

        $products = $qb
            ->select('p.id')
            ->addSelect('p.title')
            ->addSelect('p.price')
            ->from('product', 'p')
            ->setFirstResult(self::MAX_RESULTS * ($page-1))
            ->setMaxResults(self::MAX_RESULTS)
            ->executeQuery()
            ->fetchAllAssociative()
        ;

        $response = [];

        foreach ($products as $product) {
            $response[] = Product::fromArray($product);
        }

        return $response;
    }
}