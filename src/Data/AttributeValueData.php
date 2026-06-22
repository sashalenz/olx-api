<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * A category attribute value as stored on an advert, e.g.
 * `{code: 'make', value: 'jeep'}` or a multi-valued `{code, values: [...]}`.
 */
final class AttributeValueData extends OlxData
{
    /**
     * @param  array<int, string>|null  $values
     */
    public function __construct(
        public ?string $code = null,
        public int|string|null $value = null,
        public ?array $values = null,
    ) {}
}
