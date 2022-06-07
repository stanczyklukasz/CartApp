<?php

declare(strict_types=1);

namespace App\Tests\Product\Application;

use App\Product\Application\Exception\ProductNotFoundException;
use App\Product\Application\UseCase\RemoveProduct;
use App\Product\Application\UseCase\RemoveProductModel;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Entity\ProductRepository;
use App\Tests\Product\Application\Utils\InMemoryProductRepository;
use PHPUnit\Framework\TestCase;

class RemoveProductTest extends TestCase
{
    public function testThrowExceptionProductNotFoundException(): void
    {
        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->method('findById')->willReturn(null);

        $removeProduct = new RemoveProduct($productRepository);

        $this->expectException(ProductNotFoundException::class);
        $removeProduct->execute(new RemoveProductModel(1));
    }

    public function testRemoveProduct(): void
    {
        $productRepository = new InMemoryProductRepository();

        $productRepository->save(new Product("The Witcher", 999));
        $productRepository->save(new Product("The Guild", 1));
        $productRepository->save(new Product("Gothic", 500));

        $removeProduct = new RemoveProduct($productRepository);

        $removeProduct->execute(new RemoveProductModel(2));

        $this->assertTrue($productRepository->isProductWithTitleExists('The Witcher'));
        $this->assertTrue($productRepository->isProductWithTitleExists('Gothic'));

        $this->assertFalse($productRepository->isProductWithTitleExists('The Guild'));
    }
}