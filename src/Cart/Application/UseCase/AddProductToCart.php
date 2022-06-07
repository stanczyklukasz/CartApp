<?php

declare(strict_types=1);

namespace App\Cart\Application\UseCase;

use App\Cart\Application\Exception\CartNotFoundException;
use App\Cart\Application\Exception\ProductNotAddedException;
use App\Cart\Application\Exception\ProductNotFoundException;
use App\Cart\Domain\CartItem;
use App\Cart\Domain\CartRepository;
use App\Cart\Domain\Exception\MoreThan10SameItemsException;
use App\Cart\Domain\Exception\MoreThan3UniqueItemsException;
use App\Product\Application\Query\ProductQuery;

class AddProductToCart
{
    public function __construct(
        private ProductQuery $productQuery,
        private CartRepository $cartRepository
    ) {
    }

    /**
     * @throws ProductNotAddedException
     * @throws ProductNotFoundException
     * @throws CartNotFoundException
     */
    public function execute(AddProductToCartModel $model): void
    {
        $product = $this->productQuery->getProductById($model->getProductId());

        if (!$product) {
            throw new ProductNotFoundException($model->getProductId());
        }

        $cart = $this->cartRepository->findByUuid($model->getCartUuid());

        if (!$cart) {
            throw new CartNotFoundException($model->getCartUuid());
        }

        try {
            $cart->addItem(
                new CartItem(
                    $product->getId(),
                    $product->getTitle(),
                    $product->getPrice()
                )
            );
        } catch (MoreThan10SameItemsException | MoreThan3UniqueItemsException $e) {
            throw new ProductNotAddedException($e->getMessage());
        }

        $this->cartRepository->save($cart);
    }
}