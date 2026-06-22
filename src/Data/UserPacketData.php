<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * A packet the authenticated user has bought. `available` is the remaining
 * advert slots; `validTo` is when it lapses.
 */
final class UserPacketData extends OlxData
{
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?string $type = null,
        public ?int $available = null,
        public ?int $capacity = null,
        public ?string $validTo = null,
    ) {}
}
