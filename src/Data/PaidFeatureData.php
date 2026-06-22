<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * A paid promotion feature (bump / top / VIP / highlight) available for an
 * advert. Price scales with the advert's stated price and the region.
 */
final class PaidFeatureData extends OlxData
{
    public function __construct(
        public ?string $type = null,
        public ?string $name = null,
        public float|int|string|null $price = null,
        public ?string $currency = null,
        public ?string $period = null,
    ) {}
}
