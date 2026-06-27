<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * An OLX user/account.
 *
 * Two shapes share this DTO:
 *   - `/users/me` (our own account): id, name, email, phone, photo, is_business…
 *   - `/users/{id}` (a chat interlocutor/buyer): id, name, **avatar** — the
 *     buyer's display name + avatar shown in the OLX web chat. Undocumented in
 *     the OpenAPI spec but returned by the API. No email/phone for buyers.
 */
final class UserData extends OlxData
{
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $photo = null,
        public ?string $avatar = null,
        public ?bool $isBusiness = null,
        public ?string $createdAt = null,
    ) {}
}
