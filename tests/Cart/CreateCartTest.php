<?php

declare(strict_types=1);

namespace App\Tests\Cart;

use App\Cart\Application\UseCase\CreateCart;
use App\Cart\Domain\Cart;
use App\Tests\Cart\Utils\InMemoryCartRepository;
use PHPUnit\Framework\TestCase;

class CreateCartTest extends TestCase
{
    public function testCreateCart(): void
    {
        $cartRepository = new InMemoryCartRepository();

        $createCart = new CreateCart($cartRepository);

        $newCartUuid = $createCart->execute();

        $this->assertNull($cartRepository->getCartByUuid('invalid-uuid'));
        $this->assertInstanceOf(Cart::class, $cartRepository->getCartByUuid($newCartUuid));
    }
}