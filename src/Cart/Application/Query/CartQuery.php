<?php

declare(strict_types=1);


namespace App\Cart\Application\Query;

use App\Cart\Application\Dto\CartSummary;

interface CartQuery
{
    public function getCartSummary(string $uuid): ?CartSummary;
}