<?php

declare(strict_types=1);

namespace Acme;

interface Offer
{
    /** @param list<Product> $items */
    public function discountInCents(array $items): int;
}
