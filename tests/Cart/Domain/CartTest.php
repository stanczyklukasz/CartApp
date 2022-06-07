<?php

declare(strict_types=1);

namespace App\Tests\Cart\Domain;

use App\Cart\Domain\Cart;
use App\Cart\Domain\CartItem;
use App\Cart\Domain\Exception\MoreThan10SameItemsException;
use App\Cart\Domain\Exception\MoreThan3UniqueItemsException;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    public function testCannotAddMoreThan3UniqueItems(): void
    {
        $cart = new Cart();

        $cart->addItem(new CartItem(1, "Gothic", 1000));
        $cart->addItem(new CartItem(2, "The Witcher", 1000));
        $cart->addItem(new CartItem(3, "The Guild", 1000));

        $this->expectException(MoreThan3UniqueItemsException::class);
        $cart->addItem(new CartItem(4, "Total War", 1000));
    }


    public function testExpectExceptionWhenTryingAddProductWithQuantityGreaterThan10(): void
    {
        $cart = new Cart();

        $cart->addItem(new CartItem(1, 'Cyberpunk', 999, 1));

        $this->expectException(MoreThan10SameItemsException::class);
        $cart->addItem(new CartItem(2, 'Gothic', 999, 11));
    }

    public function testExpectExceptionWhenTryingAddMoreThan10SameItems(): void
    {
        $cart = new Cart();
        $cart->addItem(new CartItem(1, 'Cyberpunk', 999, 9));

        $cart->addItem(new CartItem(1, 'Cyberpunk', 999, 1));

        $this->expectException(MoreThan10SameItemsException::class);
        $cart->addItem(new CartItem(1, 'Cyberpunk', 500, 1));
    }
}