<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\ApiModels;

use Sashalenz\OlxApi\Data\LanguageData;
use Sashalenz\OlxApi\Data\Paginated;
use Sashalenz\OlxApi\Exceptions\OlxApiException;

/**
 * Partner API v2 — Languages (reference data).
 *
 * @see https://developer.olx.ua/api/doc
 */
final class Languages extends BaseModel
{
    /**
     * @return Paginated<LanguageData>
     *
     * @throws OlxApiException
     */
    public function all(): Paginated
    {
        return Paginated::fromResponse(
            $this->httpGet($this->apiPath('languages'))->all(),
            LanguageData::class,
        );
    }
}
