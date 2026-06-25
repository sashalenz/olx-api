<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\ApiModels;

use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Conditionable;
use Sashalenz\OlxApi\Exceptions\OlxApiException;
use Sashalenz\OlxApi\Request;

/**
 * Base for the fluent resource models (Adverts / Threads / Messages / …),
 * mirroring the chatwoot-api / viber-bot-api `BaseModel` shape.
 *
 * Resolution seam — per-instance override wins, else config:
 *   ->token($t) → config('olx-api.token') → `Authorization: Bearer` header
 *
 * Multi-account consumers (5 manager accounts on 5 FOPs) pass a per-account
 * access token at call time:
 *   OlxApi::adverts()->token($accessForAccountB)->all();
 *
 * Token persistence + refresh is the consumer's responsibility; use
 * OlxApi::oauth() to mint/refresh tokens.
 */
abstract class BaseModel
{
    use Conditionable;

    private ?string $token = null;

    public function token(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @throws OlxApiException
     */
    protected function resolveToken(): string
    {
        $token = $this->token ?? config('olx-api.token');

        if (empty($token) || ! is_string($token)) {
            throw new OlxApiException('OLX API access token is not configured (config olx-api.token or ->token()).');
        }

        return $token;
    }

    /**
     * Normalise a resource path. It is resolved against the Partner API base
     * (`olx-api.api_url`, e.g. https://www.olx.ua/api/partner) by the transport —
     * NOT the OAuth host. OAuth endpoints live on `olx-api.base_url`.
     */
    protected function apiPath(string $suffix): string
    {
        return ltrim($suffix, '/');
    }

    /**
     * @return array<string, string>
     *
     * @throws OlxApiException
     */
    protected function headers(): array
    {
        return [
            'Authorization' => 'Bearer '.$this->resolveToken(),
            'Version' => (string) config('olx-api.version', '2.0'),
            'Accept-Language' => (string) config('olx-api.locale', 'uk'),
        ];
    }

    /**
     * @param  array<string, mixed>  $query
     * @return Collection<string, mixed>
     *
     * @throws OlxApiException
     */
    protected function httpGet(string $path, array $query = []): Collection
    {
        return $this->dispatch('GET', $path, $query);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return Collection<string, mixed>
     *
     * @throws OlxApiException
     */
    protected function httpPost(string $path, array $body = []): Collection
    {
        return $this->dispatch('POST', $path, $body);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return Collection<string, mixed>
     *
     * @throws OlxApiException
     */
    protected function httpPut(string $path, array $body = []): Collection
    {
        return $this->dispatch('PUT', $path, $body);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return Collection<string, mixed>
     *
     * @throws OlxApiException
     */
    protected function httpDelete(string $path, array $body = []): Collection
    {
        return $this->dispatch('DELETE', $path, $body);
    }

    /**
     * Unwrap OLX's `{data: …}` envelope to the inner array (falls back to the
     * whole body when there is no `data` key).
     *
     * @param  Collection<string, mixed>  $response
     * @return array<string, mixed>
     */
    protected function dataOf(Collection $response): array
    {
        $data = $response->get('data', $response->all());

        return is_array($data) ? $data : [];
    }

    /**
     * @param  array<string, mixed>  $params
     * @return Collection<string, mixed>
     *
     * @throws OlxApiException
     */
    private function dispatch(string $method, string $path, array $params): Collection
    {
        $response = (new Request($method, $path, $params, $this->headers(), false, (string) config('olx-api.api_url')))->make();

        /** @var array<string, mixed> $json */
        $json = $response->json() ?? [];

        return collect($json);
    }
}
