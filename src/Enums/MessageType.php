<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Enums;

/**
 * Direction of a chat message within a thread, from the authenticated
 * account's perspective.
 */
enum MessageType: string
{
    /** Message we sent (our reply) — must NOT be mirrored back to Chatwoot. */
    case Sent = 'sent';

    /** Message the buyer sent to us — the inbound lead/mirror. */
    case Received = 'received';
}
