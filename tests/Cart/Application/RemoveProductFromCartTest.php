<?php

declare(strict_types=1);

namespace App\Tests\Cart\Application;

use App\Cart\Application\Exception\CartNotFoundException;
use App\Cart\Application\Exception\ProductInCartNotFoundException;
use App\Cart\Application\UseCase\RemoveProductFromCart;
use App\Cart\Application\UseCase\RemoveProductFromCartModel;
use App\Cart\Domain\Cart;
use App\Cart\Domain\CartItem;
use App\Cart\Domain\CartRepository;
use App\Tests\Cart\Application\Utils\InMemoryCartRepository;
use PHPUnit\Framework\TestCase;

class RemoveProductFromCartTest extends TestCase
{
    public function testExpectExceptionProductNotFound(): void
    {
        $cartRepositoryMock = $this->createMock(CartRepository::class);
        $cartRepositoryMock->method('findByUuid')->willReturn(null);

        $removeProductFromCartModel = new RemoveProductFromCart($cartRepositoryMock);

        $this->expectException(CartNotFoundException::class);
        $removeProductFromCartModel->execute(new RemoveProductFromCartModel('', 1));
    }

    public function testExpectExceptionCartNotFound(): void
    {
        $cartRepositoryMock = $this->createMock(CartRepository::class);
        $cartRepositoryMock->method('findByUuid')->willReturn(new Cart());

        $removeProductFromCartModel = new RemoveProductFromCart($cartRepositoryMock);

        $this->expectException(ProductInCartNotFoundException::class);
        $removeProductFromCartModel->execute(new RemoveProductFromCartModel('', 1));
    }

    public function testRemoveProductFromCart(): void
    {
        $inMemoryCartRepository = new InMemoryCartRepository();

        $cart = new Cart();
        $cart->addItem(new CartItem(1, 'Gothic', 1000));
        $cart->addItem(new CartItem(2, 'The Witcher', 1000));

        $inMemoryCartRepository->save($cart);

        $removeProductFromCartModel = new RemoveProductFromCart($inMemoryCartRepository);

        $cartUuid = $cart->getUuid()->toString();

        $removeProductFromCartModel->execute(new RemoveProductFromCartModel($cartUuid, 1));

        $resultCart = $inMemoryCartRepository->findByUuid($cartUuid);

        $resultItems = $resultCart->getItems();
        $this->assertCount(1, $resultItems);

        $leftItem = reset($resultItems);
        $this->assertSame(2, $leftItem->getExternalId());
    }
}