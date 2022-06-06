<?php

declare(strict_types=1);

namespace App\Product\Application\UseCase;

use App\Product\Application\Exception\ProductNotFoundException;
use App\Product\Application\Exception\ProductWithSameTitleExistsException;
use App\Product\Domain\Entity\ProductRepository;

class PatchProduct
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    public function execute(PatchProductModel $model): void
    {
        $product = $this->productRepository->findById($model->getId());

        if (!$product) {
            throw new ProductNotFoundException($model->getId());
        }

        if ($model->getTitle()) {
            if ($this->productRepository->isProductWithTitleExists($model->getTitle())) {
                throw new ProductWithSameTitleExistsException($model->getTitle());
            }

            $product->setTitle($model->getTitle());
        }

        if ($model->getPrice()) {
            $product->setPrice($model->getPrice());
        }

        $this->productRepository->save($product);
    }
}