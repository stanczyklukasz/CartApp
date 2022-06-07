<?php

declare(strict_types=1);

namespace App\Tests\Cart\Application;

use App\Cart\Application\UseCase\CreateCart;
use App\Cart\Domain\Cart;
use App\Tests\Cart\Application\Utils\InMemoryCartRepository;
use PHPUnit\Framework\TestCase;

class CreateCartTest extends TestCase
{
    public function testCreateCart(): void
    {
        $cartRepository = new InMemoryCartRepository();

        $createCart = new CreateCart($cartRepository);

        $newCartUuid = $createCart->execute();

        $this->assertNull($cartRepository->findByUuid('invalid-uuid'));
        $this->assertInstanceOf(Cart::class, $cartRepository->findByUuid($newCartUuid));
    }
}