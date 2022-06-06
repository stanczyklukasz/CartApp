<?php

declare(strict_types=1);

namespace App\Product\Application\UseCase;

use App\Shared\Infrastructure\Exception\AppException;

class PatchProductModel
{
    public static function fromJson(int $id, string $json): self
    {
        $data = json_decode($json, true);

        if (count(array_diff(array_keys($data), ['title', 'price'])) > 0) {
            throw new AppException("Invalid data", 400);
        }

        return new self(
            $id,
            $data['title'] ?? null,
            (int)$data['price'] ?? null
        );
    }

    public function __construct(
        private readonly int $id,
        private readonly ?string $title,
        private readonly ?int $price
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }
}