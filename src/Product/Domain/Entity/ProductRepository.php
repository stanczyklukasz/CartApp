<?php

declare(strict_types=1);

namespace App\Product\Domain\Entity;

interface ProductRepository
{
    public function isProductWithTitleExists(string $title): bool;

    public function save(Product $product, bool $flush = true);

    public function findById(int $id): ?Product;

    public function remove(Product $product, bool $flush = true): void;
}