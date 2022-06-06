<?php

declare(strict_types=1);

namespace App\Product\Application\UseCase;


use App\Product\Application\Exception\ProductWithSameTitleExistsException;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Entity\ProductRepository;

class CreateProduct
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    /**
     * @throws ProductWithSameTitleExistsException
     */
    public function execute(CreateProductModel $model): void
    {
        if ($this->productRepository->isProductWithTitleExists($model->getTitle())) {
            throw new ProductWithSameTitleExistsException($model->getTitle());
        }

        $product = new Product(
            $model->getTitle(),
            $model->getPrice()
        );

        $this->productRepository->save($product);
    }
}