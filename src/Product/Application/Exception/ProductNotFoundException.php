<?php

declare(strict_types=1);

namespace App\Product\Application\Exception;

class ProductNotFoundException extends \Exception
{
    public function __construct(int $id)
    {
        parent::__construct("Product with id: {$id} not found");
    }
}