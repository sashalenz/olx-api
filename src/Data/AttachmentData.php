<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * A file attached to a chat message (photo/document). Replies attach files by
 * pre-hosted `url` — there is no upload endpoint.
 */
final class AttachmentData extends OlxData
{
    public function __construct(
        public ?string $name = null,
        public ?string $url = null,
    ) {}
}
