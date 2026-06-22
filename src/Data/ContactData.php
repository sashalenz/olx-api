<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * Advert contact block. Note OLX masks the phone in list/detail responses and
 * exposes only a "show phone" counter via statistics — never the caller number.
 */
final class ContactData extends OlxData
{
    public function __construct(
        public ?string $name = null,
        public ?string $phone = null,
    ) {}
}
