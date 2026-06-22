<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\ApiModels;

use Sashalenz\OlxApi\Data\Paginated;
use Sashalenz\OlxApi\Data\RegionData;
use Sashalenz\OlxApi\Exceptions\OlxApiException;

/**
 * Partner API v2 — Regions (області).
 *
 * @see https://developer.olx.ua/api/doc
 */
final class Regions extends BaseModel
{
    /**
     * @return Paginated<RegionData>
     *
     * @throws OlxApiException
     */
    public function all(): Paginated
    {
        return Paginated::fromResponse(
            $this->httpGet($this->apiPath('regions'))->all(),
            RegionData::class,
        );
    }

    /**
     * @throws OlxApiException
     */
    public function get(int $regionId): RegionData
    {
        return RegionData::from($this->dataOf($this->httpGet($this->apiPath("regions/{$regionId}"))));
    }
}
