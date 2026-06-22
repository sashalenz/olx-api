<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\ApiModels;

use Sashalenz\OlxApi\Data\PacketData;
use Sashalenz\OlxApi\Data\Paginated;
use Sashalenz\OlxApi\Data\UserPacketData;
use Sashalenz\OlxApi\Data\ZoneData;
use Sashalenz\OlxApi\Exceptions\OlxApiException;

/**
 * Partner API v2 — Placement packets (bundles) and pricing zones.
 *
 * @see https://developer.olx.ua/api/doc
 */
final class Packets extends BaseModel
{
    /**
     * Available packets to buy.
     *
     * @param  array<string, mixed>  $query  optional: ['category_id'=>…, 'payment_method'=>…, 'type'=>…, 'with_features'=>true, 'zone_id'=>…]
     * @return Paginated<PacketData>
     *
     * @throws OlxApiException
     */
    public function all(array $query = []): Paginated
    {
        return Paginated::fromResponse(
            $this->httpGet($this->apiPath('packets'), $query)->all(),
            PacketData::class,
        );
    }

    /**
     * Pricing zones for a category (regional pricing).
     *
     * @param  array<string, mixed>  $query  requires ['category_id'=>…]
     * @return Paginated<ZoneData>
     *
     * @throws OlxApiException
     */
    public function zones(array $query): Paginated
    {
        return Paginated::fromResponse(
            $this->httpGet($this->apiPath('zones'), $query)->all(),
            ZoneData::class,
        );
    }

    /**
     * Packets the authenticated user already owns.
     *
     * @param  array<string, mixed>  $query  optional: ['limit'=>…, 'offset'=>…, 'availability'=>…, 'sort_by'=>…]
     * @return Paginated<UserPacketData>
     *
     * @throws OlxApiException
     */
    public function userPackets(array $query = []): Paginated
    {
        return Paginated::fromResponse(
            $this->httpGet($this->apiPath('users/me/packets'), $query)->all(),
            UserPacketData::class,
        );
    }

    /**
     * Buy a packet for the user account.
     *
     * @param  array<string, mixed>  $payload  ['category_id'=>…, 'size'=>…, 'payment_method'=>…, 'type'=>…, 'zone_id'=>…]
     * @return array<string, mixed>
     *
     * @throws OlxApiException
     */
    public function buyForUser(array $payload): array
    {
        return $this->dataOf($this->httpPost($this->apiPath('users/me/packets'), $payload));
    }
}
