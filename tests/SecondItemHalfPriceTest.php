<?php

declare(strict_types=1);

namespace Acme\Tests;

use Acme\Product;
use Acme\SecondItemHalfPrice;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class SecondItemHalfPriceTest extends TestCase
{
    #[DataProvider('redWidgetQuantities')]
    public function testDiscountForCompletePairs(int $quantity, int $expectedDiscount): void
    {
        $red = new Product('R01', 'Red Widget', 3295);
        $items = array_fill(0, $quantity, $red);
        $offer = new SecondItemHalfPrice('R01');

        $actualDiscount = $offer->discountInCents($items);

        self::assertSame($expectedDiscount, $actualDiscount);
    }

    public static function redWidgetQuantities(): array
    {
        return [
            'empty list' => [0, 0],
            'one red has no pair' => [1, 0],
            'two red form one pair' => [2, 1648],
            'three red still form one pair' => [3, 1648],
            'four red form two pairs' => [4, 3296],
            'five red still form two pairs' => [5, 3296],
        ];
    }

    public function testOtherProductsDoNotCountTowardsPairs(): void
    {
        $offer = new SecondItemHalfPrice('R01');
        $items = [
            new Product('R01', 'Red Widget', 3295),
            new Product('G01', 'Green Widget', 2495),
            new Product('B01', 'Blue Widget', 795),
        ];

        self::assertSame(0, $offer->discountInCents($items));
    }

    public function testUnrelatedProductsDoNotChangeTheDiscount(): void
    {
        $offer = new SecondItemHalfPrice('R01');
        $red = new Product('R01', 'Red Widget', 3295);
        $items = [
            $red,
            new Product('G01', 'Green Widget', 2495),
            $red,
            new Product('B01', 'Blue Widget', 795),
        ];

        self::assertSame(1648, $offer->discountInCents($items));
    }

    public function testOfferUsesTheConfiguredProductAndItsPrice(): void
    {
        $offer = new SecondItemHalfPrice('G01');
        $green = new Product('G01', 'Green Widget', 2495);

        self::assertSame(1248, $offer->discountInCents([$green, $green]));
    }

    public function testEvenCentPriceHasAnExactHalfPriceDiscount(): void
    {
        $offer = new SecondItemHalfPrice('TEST');
        $product = new Product('TEST', 'Test Widget', 1000);

        self::assertSame(500, $offer->discountInCents([$product, $product]));
    }
}
