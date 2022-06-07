<?php

declare(strict_types=1);

namespace App\Tests\Cart\Application;

use App\Cart\Application\Exception\CartNotFoundException;
use App\Cart\Application\Exception\ProductNotFoundException;
use App\Cart\Application\UseCase\AddProductToCart;
use App\Cart\Application\UseCase\AddProductToCartModel;
use App\Cart\Domain\CartRepository;
use App\Product\Application\Dto\Product;
use App\Product\Application\Query\ProductQuery;
use PHPUnit\Framework\TestCase;

class AddProductToCartTest extends TestCase
{
    public function testExpectExceptionProductNotFound(): void
    {
        $productQueryMock = $this->createMock(ProductQuery::class);
        $productQueryMock->method('getProductById')->willReturn(null);

        $addProductToCart = new AddProductToCart(
            $productQueryMock,
            $this->createMock(CartRepository::class)
        );

        $this->expectException(ProductNotFoundException::class);
        $addProductToCart->execute(new AddProductToCartModel('', 1));
    }

    public function testExpectExceptionCartNotFound(): void
    {
        $productQueryMock = $this->createMock(ProductQuery::class);
        $productQueryMock->method('getProductById')->willReturn(new Product(1,'',1));

        $cartRepositoryMock = $this->createMock(CartRepository::class);
        $cartRepositoryMock->method('findByUuid')->willReturn(null);

        $addProductToCart = new AddProductToCart($productQueryMock, $cartRepositoryMock);

        $this->expectException(CartNotFoundException::class);
        $addProductToCart->execute(new AddProductToCartModel('', 1));
    }
}