<?php

declare(strict_types=1);

namespace App\Cart\Application\Exception;

class CartNotFoundException extends \Exception
{
    public function __construct(string $cartUuid)
    {
        parent::__construct("Cart with uuid: {$cartUuid} not found");
    }
}