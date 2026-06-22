<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Exceptions;

use Exception;
use Throwable;

/**
 * Base for every OLX Partner API v2 error. Carries the parsed `error` envelope
 * OLX returns on failure:
 *
 *   {"error":{"status":400,"title":"…","detail":"…","validation":[{"field":…}]}}
 *
 * Use {@see OlxApiException::fromResponse()} to map an HTTP status + body to the
 * most specific subclass (validation / auth / not-found / rate-limit / server).
 */
class OlxApiException extends Exception
{
    /**
     * @param  array<int, array<string, mixed>>  $validation  per-field validation errors (ValidationException only)
     */
    public function __construct(
        string $message = '',
        public readonly ?int $status = null,
        public readonly ?string $title = null,
        public readonly ?string $detail = null,
        public readonly array $validation = [],
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, (int) ($status ?? 0), $previous);
    }

    /**
     * Map an HTTP status code + decoded response body to the right exception.
     *
     * @param  array<string, mixed>  $body
     */
    public static function fromResponse(int $status, array $body, ?Throwable $previous = null): self
    {
        /** @var array<string, mixed> $error */
        $error = is_array($body['error'] ?? null) ? $body['error'] : [];

        $title = isset($error['title']) && is_string($error['title']) ? $error['title'] : null;
        $detail = isset($error['detail']) && is_string($error['detail']) ? $error['detail'] : null;

        /** @var array<int, array<string, mixed>> $validation */
        $validation = is_array($error['validation'] ?? null) ? array_values($error['validation']) : [];

        $message = trim(($title ?? 'OLX API error').($detail !== null ? ': '.$detail : ''));

        $class = match (true) {
            $status === 400 || $status === 422 => ValidationException::class,
            $status === 401 => UnauthorizedException::class,
            $status === 403 => ForbiddenException::class,
            $status === 404 => NotFoundException::class,
            $status === 429 => RateLimitException::class,
            $status >= 500 => ServerException::class,
            default => self::class,
        };

        return new $class($message, $status, $title, $detail, $validation, $previous);
    }
}
