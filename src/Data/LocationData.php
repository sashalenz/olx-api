<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * Reverse-geocode result of `GET /locations?latitude=&longitude=`: the city and
 * (optionally) district matching the coordinates.
 */
final class LocationData extends OlxData
{
    public function __construct(
        public ?CityData $city = null,
        public ?DistrictData $district = null,
    ) {}
}
