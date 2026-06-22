<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * A top-level region (область).
 */
final class RegionData extends OlxData
{
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
    ) {}
}
