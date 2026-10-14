<?php

declare(strict_types=1);

namespace Acme\Tests;

use Acme\StandardDelivery;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class StandardDeliveryTest extends TestCase
{
    #[DataProvider('deliveryThresholds')]
    public function testDeliveryChargeAtThresholds(int $subtotal, int $expectedCharge): void
    {
        $delivery = new StandardDelivery();

        $actualCharge = $delivery->chargeInCents($subtotal);

        self::assertSame($expectedCharge, $actualCharge);
    }

    public static function deliveryThresholds(): array
    {
        return [
            'one cent below fifty dollars' => [4999, 495],
            'exactly fifty dollars' => [5000, 295],
            'one cent below ninety dollars' => [8999, 295],
            'exactly ninety dollars' => [9000, 0],
            'above ninety dollars' => [9001, 0],
        ];
    }
}
