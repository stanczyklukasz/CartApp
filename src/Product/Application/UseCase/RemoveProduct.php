<?php

declare(strict_types=1);

namespace App\Product\Application\UseCase;

use App\Product\Application\Exception\ProductNotFoundException;
use App\Product\Domain\Entity\ProductRepository;

class RemoveProduct
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    /**
     * @throws ProductNotFoundException
     */
    public function execute(RemoveProductModel $model): void
    {
        $product = $this->productRepository->findById($model->getId());

        if (!$product) {
            throw new ProductNotFoundException($model->getId());
        }

        $this->productRepository->remove($product);
    }
}