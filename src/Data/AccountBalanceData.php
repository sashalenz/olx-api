<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * Wallet balance for the authenticated user (`/users/me/account-balance`).
 */
final class AccountBalanceData extends OlxData
{
    public function __construct(
        public float|int|string|null $balance = null,
        public float|int|string|null $bonus = null,
        public float|int|string|null $refund = null,
        public ?string $currency = null,
    ) {}
}
