<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * Business profile data (`/users-business/me`): the OLX-store storefront fields.
 *
 * @property array<int, string>|null $phones
 */
final class BusinessUserData extends OlxData
{
    /**
     * @param  array<int, string>|null  $phones
     */
    public function __construct(
        public ?string $name = null,
        public ?string $description = null,
        public ?string $subdomain = null,
        public ?string $websiteUrl = null,
        public ?string $address = null,
        public ?array $phones = null,
    ) {}
}
