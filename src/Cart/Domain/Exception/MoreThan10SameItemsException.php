<?php

declare(strict_types=1);

namespace App\Cart\Domain\Exception;

class MoreThan10SameItemsException extends \Exception
{
    public function __construct(int $maxSameItems)
    {
        parent::__construct("You can add maximum " . $maxSameItems . " same items to cart");
    }
}