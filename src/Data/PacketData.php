<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * A purchasable placement packet offered for a category/zone. OLX serves
 * packet ids as UUID strings, hence the union type.
 */
final class PacketData extends OlxData
{
    public function __construct(
        public string|int|null $id = null,
        public ?string $type = null,
        public ?int $size = null,
        public ?string $name = null,
        public float|int|string|null $price = null,
        public ?string $currency = null,
    ) {}
}
