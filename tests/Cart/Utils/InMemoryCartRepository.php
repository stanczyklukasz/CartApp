<?php

declare(strict_types=1);

namespace App\Tests\Cart\Utils;

use App\Cart\Domain\Cart;
use App\Cart\Domain\CartRepository;

class InMemoryCartRepository implements CartRepository
{
    /** @var Cart[]  */
    private array $items = [];

    public function save(Cart $cart, bool $flush = true): void
    {
        $this->items[$cart->getUuid()->toString()] = $cart;
    }

    public function getCartByUuid(string $uuid): ?Cart
    {
        return $this->items[$uuid] ?? null;
    }
}