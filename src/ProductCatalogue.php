<?php

declare(strict_types=1);

namespace Acme;

use InvalidArgumentException;

final class ProductCatalogue
{
    private array $products = [];

    public function __construct(Product ...$products)
    {
        foreach ($products as $product) {
            if (isset($this->products[$product->code])) {
                throw new InvalidArgumentException('Duplicate product code: ' . $product->code);
            }

            $this->products[$product->code] = $product;
        }
    }

    public function get(string $code): Product
    {
        return $this->products[$code]
            ?? throw new InvalidArgumentException('Unknown product code: ' . $code);
    }

    /** @return list<Product> */
    public function all(): array
    {
        return array_values($this->products);
    }
}
