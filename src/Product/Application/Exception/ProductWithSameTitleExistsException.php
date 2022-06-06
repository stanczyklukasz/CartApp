<?php

declare(strict_types=1);

namespace App\Product\Application\Exception;

class ProductWithSameTitleExistsException extends \Exception
{
    public function __construct(string $productTitle = "")
    {
        parent::__construct("Product with title: {$productTitle} is exists");
    }
}