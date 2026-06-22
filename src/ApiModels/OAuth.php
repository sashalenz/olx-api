<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\ApiModels;

use Illuminate\Support\Traits\Conditionable;
use Sashalenz\OlxApi\Data\TokenData;
use Sashalenz\OlxApi\Exceptions\OlxApiException;
use Sashalenz\OlxApi\Request;

/**
 * OAuth2 for the Partner API v2. Two grants:
 *   - `authorization_code` — per-manager account onboarding (consent → code →
 *     token). The consumer persists the returned access + refresh tokens.
 *   - `client_credentials` — app-context, read-only reference data (categories,
 *     cities, currencies) without a user.
 *
 * Token persistence and refresh-before-expiry are the consumer's job; this
 * resource only mints/refreshes. The refresh token lapses after ~1 month of
 * inactivity, after which the account must re-authorize via {@see authorizeUrl()}.
 */
final class OAuth
{
    use Conditionable;

    private ?string $clientId = null;

    private ?string $clientSecret = null;

    private ?string $redirectUri = null;

    public function clientId(string $clientId): static
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function clientSecret(string $clientSecret): static
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    public function redirectUri(string $redirectUri): static
    {
        $this->redirectUri = $redirectUri;

        return $this;
    }

    /**
     * Build the consent URL to redirect a manager to. `state` is echoed back on
     * the callback (use it for CSRF + to identify which account is linking).
     *
     * @throws OlxApiException
     */
    public function authorizeUrl(?string $state = null, ?string $scope = null): string
    {
        $query = http_build_query(array_filter([
            'client_id' => $this->resolveClientId(),
            'response_type' => 'code',
            'scope' => $scope ?? (string) config('olx-api.scope', 'v2 read write'),
            'redirect_uri' => $this->resolveRedirectUri(),
            'state' => $state,
        ], static fn (mixed $v): bool => $v !== null && $v !== ''));

        return rtrim((string) config('olx-api.base_url'), '/').'/oauth/authorize?'.$query;
    }

    /**
     * Exchange an authorization code (from the callback) for tokens.
     *
     * @throws OlxApiException
     */
    public function exchangeCode(string $code): TokenData
    {
        return $this->token([
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->resolveRedirectUri(),
        ]);
    }

    /**
     * Refresh an access token using a stored refresh token. OLX rotates the
     * refresh token — persist the NEW one from the response.
     *
     * @throws OlxApiException
     */
    public function refresh(string $refreshToken): TokenData
    {
        return $this->token([
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ]);
    }

    /**
     * App-context token for read-only reference data (no user).
     *
     * @throws OlxApiException
     */
    public function clientCredentials(?string $scope = null): TokenData
    {
        return $this->token([
            'grant_type' => 'client_credentials',
            'scope' => $scope ?? (string) config('olx-api.scope', 'v2 read'),
        ]);
    }

    /**
     * @param  array<string, mixed>  $grant
     *
     * @throws OlxApiException
     */
    private function token(array $grant): TokenData
    {
        $response = (new Request(
            'POST',
            'api/open/oauth/token',
            [
                'client_id' => $this->resolveClientId(),
                'client_secret' => $this->resolveClientSecret(),
                ...$grant,
            ],
            [],
            asForm: true,
        ))->make();

        /** @var array<string, mixed> $json */
        $json = $response->json() ?? [];

        return TokenData::from($json);
    }

    /**
     * @throws OlxApiException
     */
    private function resolveClientId(): string
    {
        $value = $this->clientId ?? config('olx-api.client_id');

        if (empty($value) || ! is_string($value)) {
            throw new OlxApiException('OLX OAuth client_id is not configured (config olx-api.client_id).');
        }

        return $value;
    }

    /**
     * @throws OlxApiException
     */
    private function resolveClientSecret(): string
    {
        $value = $this->clientSecret ?? config('olx-api.client_secret');

        if (empty($value) || ! is_string($value)) {
            throw new OlxApiException('OLX OAuth client_secret is not configured (config olx-api.client_secret).');
        }

        return $value;
    }

    /**
     * @throws OlxApiException
     */
    private function resolveRedirectUri(): string
    {
        $value = $this->redirectUri ?? config('olx-api.redirect_uri');

        if (empty($value) || ! is_string($value)) {
            throw new OlxApiException('OLX OAuth redirect_uri is not configured (config olx-api.redirect_uri).');
        }

        return $value;
    }
}
