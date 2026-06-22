<?php

declare(strict_types=1);

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Sashalenz\OlxApi\Exceptions\OlxApiException;
use Sashalenz\OlxApi\OlxApi;

it('throws when no access token is configured or passed', function (): void {
    config()->set('olx-api.token', null);

    OlxApi::adverts()->all();
})->throws(OlxApiException::class, 'access token is not configured');

it('lets a per-call token override win over config', function (): void {
    Http::fake(['*' => Http::response(['data' => []], 200)]);

    OlxApi::threads()->token('override')->all();

    Http::assertSent(fn (Request $request): bool => $request->hasHeader('Authorization', 'Bearer override'));
});

it('sends the mandatory Version and Accept-Language headers', function (): void {
    config()->set('olx-api.version', '2.0');
    config()->set('olx-api.locale', 'uk');
    Http::fake(['*' => Http::response(['data' => []], 200)]);

    OlxApi::adverts()->all();

    Http::assertSent(fn (Request $request): bool => $request->hasHeader('Version', '2.0')
        && $request->hasHeader('Accept-Language', 'uk'));
});

it('wraps connection errors in OlxApiException', function (): void {
    Http::fake(fn () => throw new ConnectionException('dns failure'));

    OlxApi::adverts()->all();
})->throws(OlxApiException::class, 'transport error');
