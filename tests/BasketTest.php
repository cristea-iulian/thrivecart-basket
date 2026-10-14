<?php

declare(strict_types=1);

namespace Acme\Tests;

use Acme\Basket;
use Acme\Product;
use Acme\ProductCatalogue;
use Acme\SecondItemHalfPrice;
use Acme\StandardDelivery;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class BasketTest extends TestCase
{
    #[DataProvider('exampleBaskets')]
    public function testSuppliedTotals(array $codes, int $expectedTotal): void
    {
        $basket = new Basket(
            new ProductCatalogue(
                new Product('R01', 'Red Widget', 3295),
                new Product('G01', 'Green Widget', 2495),
                new Product('B01', 'Blue Widget', 795),
            ),
            new StandardDelivery(),
            new SecondItemHalfPrice('R01'),
        );

        foreach ($codes as $code) {
            $basket->add($code);
        }

        self::assertSame($expectedTotal, $basket->total());
        // Reading the total again must not apply the discount a second time.
        self::assertSame($expectedTotal, $basket->total());
    }

    public function testBasketWithoutOffersChargesFullPrice(): void
    {
        $basket = new Basket(
            new ProductCatalogue(new Product('R01', 'Red Widget', 3295)),
            new StandardDelivery(),
        );
        $basket->add('R01');
        $basket->add('R01');

        // $65.90 in products + $2.95 delivery, with no discount configured.
        self::assertSame(6885, $basket->total());
    }

    public function testUnknownProductIsRejectedWithoutChangingTheBasket(): void
    {
        $basket = new Basket(
            new ProductCatalogue(new Product('B01', 'Blue Widget', 795)),
            new StandardDelivery(),
        );
        $basket->add('B01');
        self::assertSame(1290, $basket->total());

        try {
            $basket->add('UNKNOWN');
            self::fail('Expected an unknown product to be rejected');
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Unknown product code: UNKNOWN', $exception->getMessage());
        }

        self::assertSame(1290, $basket->total());
    }

    public static function exampleBaskets(): array
    {
        return [
            'blue and green' => [['B01', 'G01'], 3785],
            'two red' => [['R01', 'R01'], 5437],
            'red and green' => [['R01', 'G01'], 6085],
            'two blue and three red' => [['B01', 'B01', 'R01', 'R01', 'R01'], 9827],
            'empty basket' => [[], 0],
        ];
    }
}
