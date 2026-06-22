<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * An available currency.
 */
final class CurrencyData extends OlxData
{
    public function __construct(
        public ?string $code = null,
        public ?string $label = null,
        public ?string $symbol = null,
    ) {}
}
