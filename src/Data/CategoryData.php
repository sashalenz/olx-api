<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * A taxonomy category. Adverts may only be placed in a leaf category. `photosLimit`
 * caps how many images the category accepts.
 */
final class CategoryData extends OlxData
{
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?int $parentId = null,
        public ?int $photosLimit = null,
    ) {}
}
