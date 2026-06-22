<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Enums;

/**
 * Advert lifecycle status as reported by the Partner API v2. Transitions are
 * driven via {@see AdvertCommand}; `new → active|blocked` is OLX-side moderation.
 */
enum AdvertStatus: string
{
    case New = 'new';
    case Active = 'active';
    case Limited = 'limited';
    case RemovedByUser = 'removed_by_user';
    case Outdated = 'outdated';
    case Unconfirmed = 'unconfirmed';
    case Unpaid = 'unpaid';
    case Moderated = 'moderated';
    case Blocked = 'blocked';
    case Disabled = 'disabled';
    case RemovedByModerator = 'removed_by_moderator';

    /**
     * Whether the advert is currently visible to buyers.
     */
    public function isVisible(): bool
    {
        return $this === self::Active;
    }
}
