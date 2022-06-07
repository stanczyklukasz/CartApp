<?php

declare(strict_types=1);

namespace App\Cart\Application\UseCase;

use App\Shared\Infrastructure\Exception\AppException;

class RemoveProductFromCartModel
{
    public static function fromJson(string $cartUuid, string $json): self
    {
        $data = json_decode($json, true);

        if (count(array_diff(array_keys($data), ['productId'])) > 0) {
            throw new AppException("Invalid data", 400);
        }

        return new self(
            $cartUuid,
            (int)$data['productId']
        );
    }

    public function __construct(
        private string $cartUuid,
        private int $productId
    ) {
    }

    public function getCartUuid(): string
    {
        return $this->cartUuid;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }
}