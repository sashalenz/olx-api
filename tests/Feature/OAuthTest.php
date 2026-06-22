<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Sashalenz\OlxApi\Exceptions\OlxApiException;
use Sashalenz\OlxApi\OlxApi;

it('builds the authorize url from config', function (): void {
    $url = OlxApi::oauth()->authorizeUrl(state: 'account-b');

    expect($url)->toStartWith('https://www.olx.test/oauth/authorize?')
        ->and($url)->toContain('client_id=client-id')
        ->and($url)->toContain('response_type=code')
        ->and($url)->toContain('state=account-b')
        ->and($url)->toContain(urlencode('https://a20.test/olx/callback'));
});

it('exchanges an authorization code for tokens (form-encoded)', function (): void {
    Http::fake(['*' => Http::response([
        'access_token' => 'at-123',
        'expires_in' => 3600,
        'token_type' => 'bearer',
        'scope' => 'v2 read write',
        'refresh_token' => 'rt-456',
    ], 200)]);

    $token = OlxApi::oauth()->exchangeCode('the-code');

    expect($token->accessToken)->toBe('at-123')
        ->and($token->expiresIn)->toBe(3600)
        ->and($token->refreshToken)->toBe('rt-456');

    Http::assertSent(fn (Request $request): bool => $request->method() === 'POST'
        && $request->url() === 'https://www.olx.test/api/open/oauth/token'
        && $request->isForm()
        && $request['grant_type'] === 'authorization_code'
        && $request['code'] === 'the-code'
        && $request['client_id'] === 'client-id'
        && $request['client_secret'] === 'client-secret');
});

it('refreshes a token', function (): void {
    Http::fake(['*' => Http::response(['access_token' => 'at-new', 'refresh_token' => 'rt-new'], 200)]);

    $token = OlxApi::oauth()->refresh('rt-old');

    expect($token->accessToken)->toBe('at-new');

    Http::assertSent(fn (Request $request): bool => $request['grant_type'] === 'refresh_token'
        && $request['refresh_token'] === 'rt-old');
});

it('requests a client-credentials token', function (): void {
    Http::fake(['*' => Http::response(['access_token' => 'cc-token'], 200)]);

    OlxApi::oauth()->clientCredentials();

    Http::assertSent(fn (Request $request): bool => $request['grant_type'] === 'client_credentials');
});

it('throws when client_id is not configured', function (): void {
    config()->set('olx-api.client_id', null);

    OlxApi::oauth()->authorizeUrl();
})->throws(OlxApiException::class, 'client_id is not configured');
