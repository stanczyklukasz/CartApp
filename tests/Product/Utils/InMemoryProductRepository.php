<?php

declare(strict_types=1);

namespace App\Tests\Product\Utils;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Entity\ProductRepository;

class InMemoryProductRepository implements ProductRepository
{
    /** @var Product[] */
    private array $products = [];

    private array $productsMap = [];

    public function isProductWithTitleExists(string $title): bool
    {
        return key_exists($title, $this->products);
    }

    public function save(Product $product, bool $flush = true)
    {
        $this->products[$product->getTitle()] = $product;
        $this->productsMap[] = $product->getTitle();
    }

    public function findById(int $id): ?Product
    {
        return $this->products[$this->productsMap[$id - 1]];
    }

    public function remove(Product $product, bool $flush = true): void
    {
        unset($this->products[array_search($product, $this->products)]);
    }

    public function getByTitle(string $title): ?Product
    {
        return $this->products[$title] ?? null;
    }
}