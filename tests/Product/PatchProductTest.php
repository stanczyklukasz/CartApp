<?php

declare(strict_types=1);

namespace App\Tests\Product;

use App\Product\Application\Exception\ProductNotFoundException;
use App\Product\Application\Exception\ProductWithSameTitleExistsException;
use App\Product\Application\UseCase\PatchProduct;
use App\Product\Application\UseCase\PatchProductModel;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Entity\ProductRepository;
use App\Tests\Product\Utils\InMemoryProductRepository;
use PHPUnit\Framework\TestCase;

class PatchProductTest extends TestCase
{
    public function testThrowExceptionProductNotFoundException(): void
    {
        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->method('findById')->willReturn(null);

        $patchProduct = new PatchProduct($productRepository);

        $this->expectException(ProductNotFoundException::class);
        $patchProduct->execute(new PatchProductModel(1, '', 0));
    }

    public function testThrowExceptionWhenProductWithNameExists(): void
    {
        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->method('findById')->willReturn(new Product('', 0));
        $productRepository->method('isProductWithTitleExists')->willReturn(true);

        $patchProduct = new PatchProduct($productRepository);

        $this->expectException(ProductWithSameTitleExistsException::class);
        $patchProduct->execute(new PatchProductModel(1, "Title", 0));
    }

    public function testPatchOnlyTitle(): void
    {
        $productRepository = new InMemoryProductRepository();
        $productRepository->save(new Product("Gothic", 100));

        $patchProduct = new PatchProduct($productRepository);

        $patchProduct->execute(new PatchProductModel(1, "The Witcher", 999));

        $this->assertEquals(
            new Product("The Witcher", 999),
            $productRepository->findById(1)
        );
    }
}