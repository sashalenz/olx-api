<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\ApiModels;

use Sashalenz\OlxApi\Data\Paginated;
use Sashalenz\OlxApi\Data\ThreadData;
use Sashalenz\OlxApi\Exceptions\OlxApiException;

/**
 * Partner API v2 — Chat threads. There is no push for messages on this API:
 * poll `all()` and act on threads whose `unreadCount > 0`.
 *
 * @see https://developer.olx.ua/api/doc
 */
final class Threads extends BaseModel
{
    /**
     * List threads.
     *
     * @param  array<string, mixed>  $query  optional: ['advert_id'=>…, 'interlocutor_id'=>…, 'offset'=>0, 'limit'=>50]
     * @return Paginated<ThreadData>
     *
     * @throws OlxApiException
     */
    public function all(array $query = []): Paginated
    {
        return Paginated::fromResponse(
            $this->httpGet($this->apiPath('threads'), $query)->all(),
            ThreadData::class,
        );
    }

    /**
     * @throws OlxApiException
     */
    public function get(int $threadId): ThreadData
    {
        return ThreadData::from($this->dataOf($this->httpGet($this->apiPath("threads/{$threadId}"))));
    }

    /**
     * Mark every message in the thread as read (`POST /threads/{id}/commands`).
     *
     * @throws OlxApiException
     */
    public function markAsRead(int $threadId): bool
    {
        $this->httpPost($this->apiPath("threads/{$threadId}/commands"), ['command' => 'mark-as-read']);

        return true;
    }

    /**
     * @throws OlxApiException
     */
    public function setFavourite(int $threadId): bool
    {
        $this->httpPost($this->apiPath("threads/{$threadId}/commands"), ['command' => 'set-favourite']);

        return true;
    }

    /**
     * @throws OlxApiException
     */
    public function unsetFavourite(int $threadId): bool
    {
        $this->httpPost($this->apiPath("threads/{$threadId}/commands"), ['command' => 'unset-favourite']);

        return true;
    }
}
