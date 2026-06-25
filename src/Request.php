<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Sashalenz\OlxApi\Exceptions\OlxApiException;
use Throwable;

/**
 * Transport layer for the OLX Partner API v2. Speaks JSON over
 * `{base_url}/api/partner/…` with `Authorization: Bearer` + `Version: 2.0`
 * (the OAuth token endpoint is the one exception: form-encoded, no bearer).
 *
 * Connection failures are retried; HTTP error statuses are NOT retried here —
 * the response status + OLX `error` envelope are mapped to the most specific
 * {@see OlxApiException} subclass so callers can react (refresh on 401, back off
 * on 429, read field errors on 400/422).
 */
final class Request
{
    private const TIMEOUT = 20;

    private const RETRY_TIMES = 2;

    private const RETRY_SLEEP = 300;

    /**
     * @param  array<string, mixed>  $params  query for GET, JSON/form body otherwise
     * @param  array<string, string>  $headers
     * @param  bool  $asForm  send the body as application/x-www-form-urlencoded (OAuth token endpoint)
     * @param  ?string  $baseUrl  base to prefix the path with; defaults to the OAuth host
     *                            (`olx-api.base_url`). Resource calls pass `olx-api.api_url`.
     */
    public function __construct(
        private readonly string $method,
        private readonly string $path,
        private readonly array $params,
        private readonly array $headers,
        private readonly bool $asForm = false,
        private readonly ?string $baseUrl = null,
    ) {}

    /**
     * @throws OlxApiException
     */
    public function make(): Response
    {
        $request = Http::timeout(self::TIMEOUT)
            // Retry transport (connection) failures only; HTTP error statuses are
            // mapped to exceptions below, so do NOT let retry throw on them.
            ->retry(self::RETRY_TIMES, self::RETRY_SLEEP, fn (Throwable $e): bool => $e instanceof ConnectionException, throw: false)
            ->baseUrl(rtrim($this->baseUrl ?? (string) config('olx-api.base_url'), '/'))
            ->withHeaders($this->headers)
            ->acceptJson();

        if ($this->asForm) {
            $request = $request->asForm();
        }

        try {
            $response = match (strtoupper($this->method)) {
                'GET' => $request->get($this->path, $this->params),
                'POST' => $request->post($this->path, $this->params),
                'PUT' => $request->put($this->path, $this->params),
                'PATCH' => $request->patch($this->path, $this->params),
                'DELETE' => $request->delete($this->path, $this->params),
                default => throw new OlxApiException("Unsupported HTTP method [{$this->method}]."),
            };
        } catch (ConnectionException $e) {
            throw new OlxApiException(
                'OLX API transport error: '.$e->getMessage(),
                previous: $e,
            );
        }

        if ($response->failed()) {
            /** @var array<string, mixed> $body */
            $body = is_array($response->json()) ? $response->json() : [];

            throw OlxApiException::fromResponse($response->status(), $body);
        }

        return $response;
    }
}
