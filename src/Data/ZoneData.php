<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * A pricing zone (regional pricing band) for a category's packets.
 */
final class ZoneData extends OlxData
{
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
    ) {}
}
