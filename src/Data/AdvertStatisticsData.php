<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * Advert statistics. `phoneViews` is the "show phone" click counter — OLX never
 * exposes the caller number or call events via the API, only this aggregate.
 */
final class AdvertStatisticsData extends OlxData
{
    public function __construct(
        public ?int $views = null,
        public ?int $phoneViews = null,
        public ?int $favourites = null,
    ) {}
}
