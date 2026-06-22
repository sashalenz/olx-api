<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\ApiModels;

use Sashalenz\OlxApi\Data\Paginated;
use Sashalenz\OlxApi\Data\PaidFeatureData;
use Sashalenz\OlxApi\Exceptions\OlxApiException;

/**
 * Partner API v2 — Paid features catalog (bump / top / VIP / highlight). To
 * buy one for a specific advert use {@see Adverts::buyPaidFeature()}.
 *
 * @see https://developer.olx.ua/api/doc
 */
final class PaidFeatures extends BaseModel
{
    /**
     * @return Paginated<PaidFeatureData>
     *
     * @throws OlxApiException
     */
    public function all(): Paginated
    {
        return Paginated::fromResponse(
            $this->httpGet($this->apiPath('paid-features'))->all(),
            PaidFeatureData::class,
        );
    }
}
