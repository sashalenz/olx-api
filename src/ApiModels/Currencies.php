<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\ApiModels;

use Sashalenz\OlxApi\Data\CurrencyData;
use Sashalenz\OlxApi\Data\Paginated;
use Sashalenz\OlxApi\Exceptions\OlxApiException;

/**
 * Partner API v2 — Currencies (reference data).
 *
 * @see https://developer.olx.ua/api/doc
 */
final class Currencies extends BaseModel
{
    /**
     * @return Paginated<CurrencyData>
     *
     * @throws OlxApiException
     */
    public function all(): Paginated
    {
        return Paginated::fromResponse(
            $this->httpGet($this->apiPath('currencies'))->all(),
            CurrencyData::class,
        );
    }
}
