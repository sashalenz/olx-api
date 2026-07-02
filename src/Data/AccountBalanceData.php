<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * Wallet balance for the authenticated user (`/users/me/account-balance`).
 * `sum` is the grand total; `wallet` is the spendable part; `bonus` and
 * `refund` are the promotional/refunded remainders. Amounts are UAH — the
 * endpoint carries no currency field.
 */
final class AccountBalanceData extends OlxData
{
    public function __construct(
        public float|int|string|null $sum = null,
        public float|int|string|null $wallet = null,
        public float|int|string|null $bonus = null,
        public float|int|string|null $refund = null,
    ) {}
}
