<?php

declare(strict_types=1);

namespace App\Cart\Domain;

use App\Cart\Domain\Exception\MoreThan10SameItemsException;
use App\Cart\Domain\Exception\MoreThan3UniqueItemsException;
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
    #[ORM\Column(name: "uuid", type: "uuid", unique: true)]
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

    /**
     * @throws MoreThan3UniqueItemsException
     * @throws MoreThan10SameItemsException
     */
    public function addItem(CartItem $newItem): void
    {
        $currentItem = $this->getCurrentItem($newItem);

        if (!$currentItem) {
            if (count($this->items) >= self::MAX_UNIQUE_ITEMS) {
                throw new MoreThan3UniqueItemsException(self::MAX_UNIQUE_ITEMS);
            }

            if ($newItem->getQuantity() >= self::MAX_STACK_ITEMS) {
                throw new MoreThan10SameItemsException(self::MAX_STACK_ITEMS);
            }

            $this->items[] = $newItem;
            return;
        }

        if ($currentItem->getQuantity() >= self::MAX_STACK_ITEMS) {
            throw new MoreThan10SameItemsException(self::MAX_STACK_ITEMS);
        }

        $currentItem->increaseQuantity();

        $currentItemKey = array_search($currentItem, $this->items);

        $this->items[$currentItemKey] = clone $currentItem;
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

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $array): void
    {
        $this->items = $array;
    }

    public function clearItems(): void
    {
        $this->items = [];
    }

    public function hasItemWithId(int $itemId): bool
    {
        foreach ($this->items as $item) {
            if ($item->getExternalId() === $itemId) {
                return true;
            }
        }

        return false;
    }

    public function removeItem(int $itemId): void
    {
        foreach ($this->items as $key => $item) {
            if ($item->getExternalId() === $itemId) {
                unset($this->items[$key]);
            }
        }

        if (!empty($this->items)) {
            $lastItem = reset($this->items);

            $this->items[array_search($lastItem, $this->items)] = clone $lastItem;
        }
    }
}