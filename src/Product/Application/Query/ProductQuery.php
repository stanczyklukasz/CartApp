<?php

declare(strict_types=1);


namespace App\Product\Application\Query;

use App\Product\Application\Dto\Product;

interface ProductQuery
{
    /** @return Product[] */
    public function getPaginatedList(int $page): array;

    public function getProductById(int $id): ?Product;
}