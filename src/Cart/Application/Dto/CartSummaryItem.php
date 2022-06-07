<?php

declare(strict_types=1);

namespace App\Cart\Application\Dto;

class CartSummaryItem
{
    public function __construct(
        private int $id,
        private string $title,
        private int $amount,
        private int $quantity
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

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}