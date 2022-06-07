<?php

declare(strict_types=1);

namespace App\Cart\Domain\Exception;

class MoreThan3UniqueItemsException extends \Exception
{
    public function __construct(int $maxUniqueItems)
    {
        parent::__construct("You can add maximum " . $maxUniqueItems . " unique items to cart");
    }
}