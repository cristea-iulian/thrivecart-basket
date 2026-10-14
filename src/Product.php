<?php

declare(strict_types=1);

namespace Acme;

final readonly class Product
{
    public function __construct(
        public string $code,
        public string $name,
        public int $priceInCents,
    ) {}
}
