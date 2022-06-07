<?php

declare(strict_types=1);

namespace App\Cart\Application\UseCase;

use App\Cart\Domain\Cart;
use App\Cart\Domain\CartRepository;

class CreateCart
{
    public function __construct(
        private CartRepository $cartRepository
    ) {
    }

    public function execute(): string
    {
        $cart = new Cart();

        $this->cartRepository->save($cart);

        return $cart->getUuid()->toString();
    }
}