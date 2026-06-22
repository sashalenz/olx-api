<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * An OLX user/account (`/users/me` or `/users/{id}`).
 */
final class UserData extends OlxData
{
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $photo = null,
        public ?bool $isBusiness = null,
        public ?string $createdAt = null,
    ) {}
}
