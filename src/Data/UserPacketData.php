<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * A packet the authenticated user has bought (`/users/me/packets`). `left` is
 * the remaining advert slots out of `size`; `activeTo` is when it lapses.
 *
 * Live-payload notes: ids are UUID strings; EXPIRED packets stay in the list
 * with `left` > 0 — filter on `isActive` before summing capacity. The packet's
 * category coverage IS exposed via `categoriesIds` / `categoriesLabels`.
 */
final class UserPacketData extends OlxData
{
    public function __construct(
        public string|int|null $id = null,
        public ?string $name = null,
        public ?bool $isActive = null,
        public ?int $size = null,
        public ?int $left = null,
        public ?string $activeTo = null,
        public float|int|string|null $price = null,
        public ?bool $isPremium = null,
        public ?string $packageType = null,
        /** @var string[]|null */
        public ?array $categoriesLabels = null,
        /** @var string[]|null */
        public ?array $categoriesIds = null,
    ) {}
}
