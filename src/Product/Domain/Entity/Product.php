<?php

declare(strict_types=1);

namespace App\Product\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(name: "title", type: "string", unique: true)]
    private string $title;

    #[ORM\Column(name: "price", type: "integer")]
    private int $price;

    public function __construct(
        string $title,
        int $price
    ) {
        $this->title = $title;
        $this->price = $price;
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

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }
}