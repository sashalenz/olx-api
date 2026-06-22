<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * A city/town. `id` is what an advert's `location.city_id` references.
 */
final class CityData extends OlxData
{
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?int $regionId = null,
        public ?float $latitude = null,
        public ?float $longitude = null,
    ) {}
}
