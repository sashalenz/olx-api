<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * A category attribute definition (e.g. make/model/condition). `validation`
 * holds OLX's rule block (required, type, …); `values` are the allowed options
 * for enum-style attributes.
 */
final class CategoryAttributeData extends OlxData
{
    /**
     * @param  array<string, mixed>  $validation
     * @param  array<int, array<string, mixed>>  $values
     */
    public function __construct(
        public ?string $code = null,
        public ?string $label = null,
        public ?string $unit = null,
        public array $validation = [],
        public array $values = [],
    ) {}
}
