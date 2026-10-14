<?php

declare(strict_types=1);

namespace Acme;

interface DeliveryRule
{
    public function chargeInCents(int $subtotalInCents): int;
}
