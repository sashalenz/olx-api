<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * A chat thread between the account and a buyer about an advert. Poll
 * `unreadCount > 0` to find threads with new buyer messages. `interlocutorId`
 * is the per-account buyer handle → use it as the transport uid when bridging
 * to Chatwoot.
 */
final class ThreadData extends OlxData
{
    public function __construct(
        public ?int $id = null,
        public ?int $advertId = null,
        public ?int $interlocutorId = null,
        public ?int $totalCount = null,
        public ?int $unreadCount = null,
        public ?string $createdAt = null,
        public ?bool $isFavourite = null,
    ) {}
}
