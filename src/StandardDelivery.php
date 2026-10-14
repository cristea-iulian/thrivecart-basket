<?php

declare(strict_types=1);

namespace Acme;

final class StandardDelivery implements DeliveryRule
{
    public function chargeInCents(int $subtotalInCents): int
    {
        return match (true) {
            $subtotalInCents < 5000 => 495,
            $subtotalInCents < 9000 => 295,
            default => 0,
        };
    }
}
