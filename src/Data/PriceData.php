<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * Advert price. `value` is numeric; `currency` is an uppercase ISO code (UAH).
 * `negotiable`/`trade`/`budget` are the OLX price flags.
 */
final class PriceData extends OlxData
{
    public function __construct(
        public float|int|string|null $value = null,
        public ?string $currency = null,
        public ?bool $negotiable = null,
        public ?bool $trade = null,
        public ?bool $budget = null,
    ) {}
}
