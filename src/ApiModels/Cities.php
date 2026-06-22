<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\ApiModels;

use Sashalenz\OlxApi\Data\CityData;
use Sashalenz\OlxApi\Data\DistrictData;
use Sashalenz\OlxApi\Data\Paginated;
use Sashalenz\OlxApi\Exceptions\OlxApiException;

/**
 * Partner API v2 — Cities (and their districts).
 *
 * @see https://developer.olx.ua/api/doc
 */
final class Cities extends BaseModel
{
    /**
     * @param  array<string, mixed>  $query  optional: ['offset'=>0, 'limit'=>100]
     * @return Paginated<CityData>
     *
     * @throws OlxApiException
     */
    public function all(array $query = []): Paginated
    {
        return Paginated::fromResponse(
            $this->httpGet($this->apiPath('cities'), $query)->all(),
            CityData::class,
        );
    }

    /**
     * @throws OlxApiException
     */
    public function get(int $cityId): CityData
    {
        return CityData::from($this->dataOf($this->httpGet($this->apiPath("cities/{$cityId}"))));
    }

    /**
     * Districts of a city.
     *
     * @return Paginated<DistrictData>
     *
     * @throws OlxApiException
     */
    public function districts(int $cityId): Paginated
    {
        return Paginated::fromResponse(
            $this->httpGet($this->apiPath("cities/{$cityId}/districts"))->all(),
            DistrictData::class,
        );
    }
}
