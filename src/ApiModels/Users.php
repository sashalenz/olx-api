<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\ApiModels;

use Sashalenz\OlxApi\Data\AccountBalanceData;
use Sashalenz\OlxApi\Data\BillingEntryData;
use Sashalenz\OlxApi\Data\Paginated;
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
     * Fetch a user by id — chiefly a chat **interlocutor** (the buyer you are
     * messaging): returns their display name + avatar ({@see UserData::$name},
     * {@see UserData::$avatar}).
     *
     * NB: `GET /users/{id}` is NOT in the OLX Partner API OpenAPI spec, but it is
     * live and returns `{id, name, avatar}`. Don't drop it for "not being in the
     * docs" — it is the only way to resolve a buyer's name/avatar (a thread
     * exposes just `interlocutor_id`, no contact details).
     *
     * @throws OlxApiException
     */
    public function get(int $userId): UserData
    {
        return UserData::from($this->dataOf($this->httpGet($this->apiPath("users/{$userId}"))));
    }

    /**
     * Wallet balance (sum/wallet/bonus/refund, UAH).
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
     * Billing history — the wallet charges (packet purchases, paid features,
     * per-advert fees), newest first.
     *
     * @param  array<string, mixed>  $query  optional: ['page'=>1, 'limit'=>…]
     * @return Paginated<BillingEntryData>
     *
     * @throws OlxApiException
     */
    public function billing(array $query = []): Paginated
    {
        return Paginated::fromResponse(
            $this->httpGet($this->apiPath('users/me/billing'), $query)->all(),
            BillingEntryData::class,
        );
    }

    /**
     * Prepaid (top-up) invoices — READ-ONLY list; the Partner API has no
     * invoice-creation endpoint (top-ups happen in the OLX UI). NB: OLX Ukraine
     * answers 403 "Feature is disabled for this country" here.
     *
     * @param  array<string, mixed>  $query  optional: ['page'=>1, 'limit'=>…]
     * @return array<string, mixed>
     *
     * @throws OlxApiException
     */
    public function prepaidInvoices(array $query = []): array
    {
        return $this->dataOf($this->httpGet($this->apiPath('users/me/prepaid-invoices'), $query));
    }

    /**
     * Postpaid (monthly business billing) invoices — READ-ONLY list, empty
     * unless the account is on postpaid terms with OLX.
     *
     * @param  array<string, mixed>  $query  optional: ['page'=>1, 'limit'=>…]
     * @return array<string, mixed>
     *
     * @throws OlxApiException
     */
    public function postpaidInvoices(array $query = []): array
    {
        return $this->dataOf($this->httpGet($this->apiPath('users/me/postpaid-invoices'), $query));
    }
}
