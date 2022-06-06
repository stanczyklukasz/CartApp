<?php

declare(strict_types=1);

namespace App\Product\Application\UseCase;

class RemoveProductModel
{
    public function __construct(
        private int $id
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }
}