<?php

declare(strict_types=1);

namespace App\Cart\Application\Dto;

use App\Cart\Domain\CartItem;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class CartSummary
{
    /**
     * @OA\Property(type="array", @OA\Items(type="object", ref=@Model(type=CartSummaryItem::class)))
     */
    private array $items = [];

    public function __construct(
        private string $uuid,
        private int $amount,
        array $items
    ) {
        $this->items = $items;
    }

    public static function fromDbalResponse(array $cart): self
    {
        /** @var CartItem[] $serializedItems */
        $serializedItems = unserialize($cart['items']);

        $amount = 0;
        $items = [];

        foreach ($serializedItems as $item) {
            $items[] = new CartSummaryItem(
                $item->getExternalId(),
                $item->getTitle(),
                $item->getPrice(),
                $item->getQuantity()
            );

            $amount += $item->getGeneralAmount();
        }

        return new self($cart['id'], $amount, $items);
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}