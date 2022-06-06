<?php

declare(strict_types=1);

namespace App\Product\Application\Dto;

class Product
{
    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['title'],
            $data['price']
        );
    }

    public function __construct(
        private readonly int $id,
        private readonly string $title,
        private readonly int $price,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
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