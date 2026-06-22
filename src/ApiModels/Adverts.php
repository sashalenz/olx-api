<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\ApiModels;

use Sashalenz\OlxApi\Data\AdvertData;
use Sashalenz\OlxApi\Data\AdvertStatisticsData;
use Sashalenz\OlxApi\Data\Paginated;
use Sashalenz\OlxApi\Data\PaidFeatureData;
use Sashalenz\OlxApi\Enums\AdvertCommand;
use Sashalenz\OlxApi\Exceptions\OlxApiException;

/**
 * Partner API v2 — Adverts (listings).
 *
 * @see https://developer.olx.ua/api/doc
 */
final class Adverts extends BaseModel
{
    /**
     * List the account's adverts.
     *
     * @param  array<string, mixed>  $query  optional: ['offset'=>0, 'limit'=>50, 'external_id'=>…, 'category_ids'=>[…]]
     * @return Paginated<AdvertData>
     *
     * @throws OlxApiException
     */
    public function all(array $query = []): Paginated
    {
        return Paginated::fromResponse(
            $this->httpGet($this->apiPath('adverts'), $query)->all(),
            AdvertData::class,
        );
    }

    /**
     * @throws OlxApiException
     */
    public function get(int $advertId): AdvertData
    {
        return AdvertData::from($this->dataOf($this->httpGet($this->apiPath("adverts/{$advertId}"))));
    }

    /**
     * Create an advert. Required body fields: title, description, category_id,
     * advertiser_type, contact, location, attributes, price (per category).
     * Images are pre-hosted URLs: `images => [['url' => …], …]`.
     *
     * @param  array<string, mixed>  $attributes
     *
     * @throws OlxApiException
     */
    public function create(array $attributes): AdvertData
    {
        return AdvertData::from($this->dataOf($this->httpPost($this->apiPath('adverts'), $attributes)));
    }

    /**
     * Update an advert. NB: editing may send the advert back to OLX moderation.
     *
     * @param  array<string, mixed>  $attributes
     *
     * @throws OlxApiException
     */
    public function update(int $advertId, array $attributes): AdvertData
    {
        return AdvertData::from($this->dataOf($this->httpPut($this->apiPath("adverts/{$advertId}"), $attributes)));
    }

    /**
     * Delete an advert (must be deactivated first). Throttling cost 5 — batch
     * with back-off. Returns true on success.
     *
     * @throws OlxApiException
     */
    public function delete(int $advertId): bool
    {
        $this->httpDelete($this->apiPath("adverts/{$advertId}"));

        return true;
    }

    /**
     * Run a lifecycle command (`POST /adverts/{id}/commands`).
     *
     * @param  array<string, mixed>  $extra  e.g. ['is_success' => true] for deactivate/finish
     *
     * @throws OlxApiException
     */
    public function command(int $advertId, AdvertCommand $command, array $extra = []): bool
    {
        $this->httpPost($this->apiPath("adverts/{$advertId}/commands"), [
            'command' => $command->value,
            ...$extra,
        ]);

        return true;
    }

    /**
     * @throws OlxApiException
     */
    public function activate(int $advertId): bool
    {
        return $this->command($advertId, AdvertCommand::Activate);
    }

    /**
     * @throws OlxApiException
     */
    public function deactivate(int $advertId, bool $isSuccess = false): bool
    {
        return $this->command($advertId, AdvertCommand::Deactivate, ['is_success' => $isSuccess]);
    }

    /**
     * Mark the advert finished (sold). Pass whether the sale succeeded.
     *
     * @throws OlxApiException
     */
    public function finish(int $advertId, bool $isSuccess = true): bool
    {
        return $this->command($advertId, AdvertCommand::Finish, ['is_success' => $isSuccess]);
    }

    /**
     * @throws OlxApiException
     */
    public function extend(int $advertId): bool
    {
        return $this->command($advertId, AdvertCommand::Extend);
    }

    /**
     * Advert statistics (views, "show phone" clicks, observers).
     *
     * @throws OlxApiException
     */
    public function statistics(int $advertId): AdvertStatisticsData
    {
        return AdvertStatisticsData::from($this->dataOf($this->httpGet($this->apiPath("adverts/{$advertId}/statistics"))));
    }

    /**
     * Moderation reason for a `blocked`/`removed_by_moderator` advert.
     *
     * @return array<string, mixed>
     *
     * @throws OlxApiException
     */
    public function moderationReason(int $advertId): array
    {
        return $this->dataOf($this->httpGet($this->apiPath("adverts/{$advertId}/moderation-reason")));
    }

    /**
     * Buy a placement packet for a single advert (`POST /adverts/{id}/packets`).
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     *
     * @throws OlxApiException
     */
    public function buyPacket(int $advertId, array $payload): array
    {
        return $this->dataOf($this->httpPost($this->apiPath("adverts/{$advertId}/packets"), $payload));
    }

    /**
     * Active paid features on the advert.
     *
     * @return Paginated<PaidFeatureData>
     *
     * @throws OlxApiException
     */
    public function paidFeatures(int $advertId): Paginated
    {
        return Paginated::fromResponse(
            $this->httpGet($this->apiPath("adverts/{$advertId}/paid-features"))->all(),
            PaidFeatureData::class,
        );
    }

    /**
     * Purchase a paid feature (bump/top/VIP) for the advert.
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     *
     * @throws OlxApiException
     */
    public function buyPaidFeature(int $advertId, array $payload): array
    {
        return $this->dataOf($this->httpPost($this->apiPath("adverts/{$advertId}/paid-features"), $payload));
    }
}
