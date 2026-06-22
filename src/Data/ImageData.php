<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * An advert image. OLX hosts the file itself — on create/update you only ever
 * pass a pre-hosted `url`; there is no direct upload endpoint.
 */
final class ImageData extends OlxData
{
    public function __construct(
        public ?string $url = null,
        public ?int $width = null,
        public ?int $height = null,
    ) {}
}
