<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\ApiModels;

use Sashalenz\OlxApi\Data\AccountBalanceData;
use Sashalenz\OlxApi\Data\UserData;
use Sashalenz\OlxApi\Exceptions\OlxApiException;

/**
 * Partner API v2 — User/account info.
 *
 * @see https://developer.olx.ua/api/doc
 */
final class Users extends BaseModel
{
    /**
     * The authenticated user (a token/health check after OAuth onboarding).
     *
     * @throws OlxApiException
     */
    public function me(): UserData
    {
        return UserData::from($this->dataOf($this->httpGet($this->apiPath('users/me'))));
    }

    /**
     * @throws OlxApiException
     */
    public function get(int $userId): UserData
    {
        return UserData::from($this->dataOf($this->httpGet($this->apiPath("users/{$userId}"))));
    }

    /**
     * Wallet balance (bonus/refund/currency).
     *
     * @throws OlxApiException
     */
    public function accountBalance(): AccountBalanceData
    {
        return AccountBalanceData::from($this->dataOf($this->httpGet($this->apiPath('users/me/account-balance'))));
    }

    /**
     * Available payment methods.
     *
     * @return array<string, mixed>
     *
     * @throws OlxApiException
     */
    public function paymentMethods(): array
    {
        return $this->dataOf($this->httpGet($this->apiPath('users/me/payment-methods')));
    }

    /**
     * Billing history.
     *
     * @param  array<string, mixed>  $query  optional: ['page'=>1, 'limit'=>…]
     * @return array<string, mixed>
     *
     * @throws OlxApiException
     */
    public function billing(array $query = []): array
    {
        return $this->dataOf($this->httpGet($this->apiPath('users/me/billing'), $query));
    }
}
