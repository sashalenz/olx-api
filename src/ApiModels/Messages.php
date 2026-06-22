<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\ApiModels;

use Sashalenz\OlxApi\Data\MessageData;
use Sashalenz\OlxApi\Data\Paginated;
use Sashalenz\OlxApi\Exceptions\OlxApiException;

/**
 * Partner API v2 — Messages within a chat thread.
 *
 * @see https://developer.olx.ua/api/doc
 */
final class Messages extends BaseModel
{
    /**
     * List messages of a thread.
     *
     * @param  array<string, mixed>  $query  optional paging
     * @return Paginated<MessageData>
     *
     * @throws OlxApiException
     */
    public function all(int $threadId, array $query = []): Paginated
    {
        return Paginated::fromResponse(
            $this->httpGet($this->apiPath("threads/{$threadId}/messages"), $query)->all(),
            MessageData::class,
        );
    }

    /**
     * @throws OlxApiException
     */
    public function get(int $threadId, int $messageId): MessageData
    {
        return MessageData::from($this->dataOf($this->httpGet($this->apiPath("threads/{$threadId}/messages/{$messageId}"))));
    }

    /**
     * Post a reply to a thread. Attachments are pre-hosted URLs:
     * `attachments => [['url' => …], …]`. Never put phone numbers in the body —
     * OLX strips contacts and may re-moderate/block.
     *
     * @param  array<int, array{url:string}>  $attachments
     *
     * @throws OlxApiException
     */
    public function post(int $threadId, string $text, array $attachments = []): MessageData
    {
        /** @var array<string, mixed> $body */
        $body = ['text' => $text];

        if ($attachments !== []) {
            $body['attachments'] = $attachments;
        }

        return MessageData::from($this->dataOf($this->httpPost($this->apiPath("threads/{$threadId}/messages"), $body)));
    }
}
