<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\ApiModels;

use Sashalenz\OlxApi\Data\DistrictData;
use Sashalenz\OlxApi\Data\Paginated;
use Sashalenz\OlxApi\Exceptions\OlxApiException;

/**
 * Partner API v2 — Districts (райони).
 *
 * @see https://developer.olx.ua/api/doc
 */
final class Districts extends BaseModel
{
    /**
     * @param  array<string, mixed>  $query
     * @return Paginated<DistrictData>
     *
     * @throws OlxApiException
     */
    public function all(array $query = []): Paginated
    {
        return Paginated::fromResponse(
            $this->httpGet($this->apiPath('districts'), $query)->all(),
            DistrictData::class,
        );
    }

    /**
     * @throws OlxApiException
     */
    public function get(int $districtId): DistrictData
    {
        return DistrictData::from($this->dataOf($this->httpGet($this->apiPath("districts/{$districtId}"))));
    }
}
