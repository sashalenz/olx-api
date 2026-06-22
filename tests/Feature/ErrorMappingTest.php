<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Sashalenz\OlxApi\Exceptions\ForbiddenException;
use Sashalenz\OlxApi\Exceptions\NotFoundException;
use Sashalenz\OlxApi\Exceptions\OlxApiException;
use Sashalenz\OlxApi\Exceptions\RateLimitException;
use Sashalenz\OlxApi\Exceptions\ServerException;
use Sashalenz\OlxApi\Exceptions\UnauthorizedException;
use Sashalenz\OlxApi\Exceptions\ValidationException;
use Sashalenz\OlxApi\OlxApi;

it('maps 401 to UnauthorizedException', function (): void {
    Http::fake(['*' => Http::response(['error' => ['status' => 401, 'title' => 'Unauthorized']], 401)]);

    OlxApi::adverts()->all();
})->throws(UnauthorizedException::class);

it('maps 403 to ForbiddenException', function (): void {
    Http::fake(['*' => Http::response(['error' => ['status' => 403, 'title' => 'Forbidden']], 403)]);

    OlxApi::adverts()->all();
})->throws(ForbiddenException::class);

it('maps 404 to NotFoundException', function (): void {
    Http::fake(['*' => Http::response(['error' => ['status' => 404, 'title' => 'Not found']], 404)]);

    OlxApi::adverts()->get(999);
})->throws(NotFoundException::class);

it('maps 429 to RateLimitException', function (): void {
    Http::fake(['*' => Http::response(['error' => ['status' => 429, 'title' => 'Too many requests']], 429)]);

    OlxApi::adverts()->all();
})->throws(RateLimitException::class);

it('maps 5xx to ServerException', function (): void {
    Http::fake(['*' => Http::response(['error' => ['status' => 500, 'title' => 'Server error']], 500)]);

    OlxApi::adverts()->all();
})->throws(ServerException::class);

it('maps 400 to ValidationException and exposes field errors', function (): void {
    Http::fake(['*' => Http::response(['error' => [
        'status' => 400,
        'title' => 'Validation Failed',
        'detail' => 'Invalid request',
        'validation' => [['field' => 'title', 'title' => 'too_short', 'detail' => 'min 16 chars']],
    ]], 400)]);

    try {
        OlxApi::adverts()->create(['title' => 'x']);
        $this->fail('expected ValidationException');
    } catch (ValidationException $e) {
        expect($e)->toBeInstanceOf(OlxApiException::class)
            ->and($e->status)->toBe(400)
            ->and($e->title)->toBe('Validation Failed')
            ->and($e->validation[0]['field'])->toBe('title')
            ->and($e->getMessage())->toContain('Invalid request');
    }
});
