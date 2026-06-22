<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * An available interface/content language.
 */
final class LanguageData extends OlxData
{
    public function __construct(
        public ?string $code = null,
        public ?string $name = null,
    ) {}
}
