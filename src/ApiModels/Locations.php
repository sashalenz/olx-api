<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\ApiModels;

use Sashalenz\OlxApi\Data\LocationData;
use Sashalenz\OlxApi\Exceptions\OlxApiException;

/**
 * Partner API v2 — Locations (reverse-geocode a coordinate to city/district).
 *
 * @see https://developer.olx.ua/api/doc
 */
final class Locations extends BaseModel
{
    /**
     * Resolve the city/district for a lat/lon pair.
     *
     * @throws OlxApiException
     */
    public function byCoordinates(float $latitude, float $longitude): LocationData
    {
        return LocationData::from($this->dataOf($this->httpGet($this->apiPath('locations'), [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ])));
    }
}
