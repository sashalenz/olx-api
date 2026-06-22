<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * A city district (район). Conditionally required on advert location for large
 * cities.
 */
final class DistrictData extends OlxData
{
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?int $cityId = null,
    ) {}
}
