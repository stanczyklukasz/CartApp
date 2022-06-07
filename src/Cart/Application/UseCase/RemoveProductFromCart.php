<?php

declare(strict_types=1);

namespace App\Cart\Application\UseCase;

use App\Cart\Application\Exception\CartNotFoundException;
use App\Cart\Application\Exception\ProductInCartNotFoundException;
use App\Cart\Domain\CartRepository;

class RemoveProductFromCart
{
    public function __construct(
        private CartRepository $cartRepository
    )
    {
    }

    public function execute(RemoveProductFromCartModel $model): void
    {
        $cart = $this->cartRepository->findByUuid($model->getCartUuid());

        if (!$cart) {
            throw new CartNotFoundException($model->getCartUuid());
        }

        if (!$cart->hasItemWithId($model->getProductId())) {
            throw new ProductInCartNotFoundException($model->getProductId());
        }

        $cart->removeItem($model->getProductId());

        $this->cartRepository->save($cart);
    }
}