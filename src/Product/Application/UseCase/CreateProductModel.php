<?php

declare(strict_types=1);

namespace App\Product\Application\UseCase;

use App\Shared\Infrastructure\Exception\AppException;

class CreateProductModel
{
    public static function fromJson(string $json): self
    {
        $data = json_decode($json, true);

        if (count(array_diff(array_keys($data), ['title', 'price'])) > 0) {
            throw new AppException("Invalid data", 400);
        }

        return new self(
            $data['title'],
            (int)$data['price']
        );
    }

    public function __construct(
        private string $title,
        private int $price
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPrice(): int
    {
        return $this->price;
    }
}