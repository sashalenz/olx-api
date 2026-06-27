<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

use Sashalenz\OlxApi\Enums\MessageType;
use Spatie\LaravelData\Attributes\DataCollectionOf;

/**
 * A single chat message in a thread. `type` is `received` (buyer → us, the
 * lead) or `sent` (us → buyer). Only `received` should be mirrored to Chatwoot;
 * mirroring `sent` would echo our own replies.
 */
final class MessageData extends OlxData
{
    /**
     * @param  array<int, AttachmentData>  $attachments
     * @param  array<int, AttachmentData>  $cvs
     */
    public function __construct(
        public ?int $id = null,
        public ?int $threadId = null,
        public ?string $type = null,
        public ?string $text = null,
        public ?bool $isRead = null,
        #[DataCollectionOf(AttachmentData::class)]
        public array $attachments = [],
        #[DataCollectionOf(AttachmentData::class)]
        public array $cvs = [],
        public ?string $createdAt = null,
        // Optional phone a buyer attaches to a message (Jobs categories; usually
        // empty for other categories). Present in the API response, undocumented.
        public ?string $phone = null,
    ) {}

    public function typeEnum(): ?MessageType
    {
        return $this->type === null ? null : MessageType::tryFrom($this->type);
    }

    public function isReceived(): bool
    {
        return $this->type === MessageType::Received->value;
    }
}
