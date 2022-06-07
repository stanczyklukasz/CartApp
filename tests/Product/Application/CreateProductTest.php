<?php

declare(strict_types=1);

namespace App\Tests\Product\Application;

use App\Product\Application\Exception\ProductWithSameTitleExistsException;
use App\Product\Application\UseCase\CreateProduct;
use App\Product\Application\UseCase\CreateProductModel;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Entity\ProductRepository;
use App\Tests\Product\Application\Utils\InMemoryProductRepository;
use PHPUnit\Framework\TestCase;

class CreateProductTest extends TestCase
{
    public function testThrowExceptionWhenProductWithNameExists(): void
    {
        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->method('isProductWithTitleExists')->willReturn(true);

        $createProduct = new CreateProduct($productRepository);

        $this->expectException(ProductWithSameTitleExistsException::class);
        $createProduct->execute(new CreateProductModel('', 0));
    }

    public function testCreateNewProduct(): void
    {
        $productRepository = new InMemoryProductRepository();

        $createProduct = new CreateProduct($productRepository);

        $createProduct->execute(new CreateProductModel("Gothic", 1000));

        $this->assertEquals(
            new Product("Gothic", 1000),
            $productRepository->getByTitle("Gothic"),
        );
    }
}