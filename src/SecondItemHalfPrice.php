<?php

declare(strict_types=1);

namespace Acme;

final readonly class SecondItemHalfPrice implements Offer
{
    public function __construct(private string $productCode)
    {
    }

    public function discountInCents(array $items): int
    {
        $quantity = 0;
        $priceInCents = 0;

        foreach ($items as $item) {
            if ($item->code === $this->productCode) {
                $quantity++;
                $priceInCents = $item->priceInCents;
            }
        }

        $pairs = intdiv($quantity, 2);

        // Round each half-price item's charge down to match the supplied totals.
        $discountPerPair = $priceInCents - intdiv($priceInCents, 2);

        return $pairs * $discountPerPair;
    }
}
