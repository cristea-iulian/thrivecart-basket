<?php

declare(strict_types=1);

namespace Acme;

final class Basket
{
    private array $items = [];

    /** @var list<Offer> */
    private readonly array $offers;

    public function __construct(
        private readonly ProductCatalogue $catalogue,
        private readonly DeliveryRule $delivery,
        Offer ...$offers,
    ) {
        $this->offers = $offers;
    }

    public function add(string $productCode): void
    {
        $product = $this->catalogue->get($productCode);
        $this->items[] = $product;
    }

    public function total(): int
    {
        if ($this->items === []) {
            return 0;
        }

        $subtotal = 0;
        foreach ($this->items as $item) {
            $subtotal += $item->priceInCents;
        }

        $discount = 0;
        foreach ($this->offers as $offer) {
            $discount += $offer->discountInCents($this->items);
        }

        $discountedSubtotal = max(0, $subtotal - $discount);

        return $discountedSubtotal + $this->delivery->chargeInCents($discountedSubtotal);
    }
}
