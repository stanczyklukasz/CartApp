<?php

declare(strict_types=1);

namespace App\Cart\Domain;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[Entity]
class Cart
{
    private const MAX_UNIQUE_ITEMS = 3;
    private const MAX_STACK_ITEMS = 10;

    #[ORM\Id]
    #[ORM\Column(name: "id", type: "uuid", unique: true)]
    private UuidInterface $uuid;

    /** @var CartItem[] */
    #[ORM\Column(name: "items", type: 'array')]
    private array $items = [];

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function addItem(CartItem $newItem): void
    {
        $currentItem = $this->getCurrentItem($newItem);

        if (!$currentItem) {
            //nie mamy takiego przedmiotu w koszyku

            //sprawdzamy czy możemy dodać go do koszyka
            if (count($this->items) >= self::MAX_UNIQUE_ITEMS) {
                throw new \Exception("You can add maximum" . self::MAX_UNIQUE_ITEMS . " unique items to cart");
            }

            $this->items[] = $newItem;
            return;
        }

        if ($currentItem->getQuantity() >= self::MAX_STACK_ITEMS) {
            throw new \Exception("You can add maximum " . self::MAX_STACK_ITEMS . " items to stack");
        }
    }

    private function getCurrentItem(CartItem $newItem): ?CartItem
    {
        $currentItem = null;

        foreach ($this->items as $item) {
            if ($newItem->isSame($item)) {
                $currentItem = $item;
                break;
            }
        }
        return $currentItem;
    }
}