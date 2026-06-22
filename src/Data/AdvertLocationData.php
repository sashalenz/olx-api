<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * Advert location as returned in a detail/list response (nested city/district/
 * region objects + coordinates). On create/update you instead send the flat
 * `{city_id, district_id, latitude, longitude}` form.
 */
final class AdvertLocationData extends OlxData
{
    public function __construct(
        public ?CityData $city = null,
        public ?DistrictData $district = null,
        public ?RegionData $region = null,
        public ?float $latitude = null,
        public ?float $longitude = null,
    ) {}
}
