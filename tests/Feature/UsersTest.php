<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Sashalenz\OlxApi\OlxApi;

it('fetches the authenticated user', function (): void {
    Http::fake(['*' => Http::response(['data' => ['id' => 42, 'name' => 'Розборка А20', 'is_business' => true]], 200)]);

    $user = OlxApi::users()->me();

    expect($user->id)->toBe(42)
        ->and($user->isBusiness)->toBeTrue();

    Http::assertSent(fn (Request $request): bool => $request->url() === 'https://www.olx.test/api/open/users/me');
});

it('reads account balance', function (): void {
    Http::fake(['*' => Http::response(['data' => ['balance' => 1200, 'bonus' => 50, 'currency' => 'UAH']], 200)]);

    expect(OlxApi::users()->accountBalance()->currency)->toBe('UAH');
});

it('reads payment methods and billing', function (): void {
    Http::fake([
        '*/users/me/payment-methods' => Http::response(['data' => ['methods' => ['account']]], 200),
        '*/users/me/billing*' => Http::response(['data' => ['items' => []]], 200),
    ]);

    expect(OlxApi::users()->paymentMethods())->toHaveKey('methods')
        ->and(OlxApi::users()->billing(['page' => 1]))->toHaveKey('items');
});

it('reads and updates the business profile', function (): void {
    Http::fake(['*' => Http::response(['data' => ['name' => 'А20', 'subdomain' => 'a20razborka']], 200)]);

    expect(OlxApi::usersBusiness()->me()->subdomain)->toBe('a20razborka');

    OlxApi::usersBusiness()->update(['description' => 'Авторозборка']);

    Http::assertSent(fn (Request $request): bool => $request->method() === 'PUT'
        && $request->url() === 'https://www.olx.test/api/open/users-business/me'
        && $request['description'] === 'Авторозборка');
});

it('manages business banners', function (): void {
    Http::fake(['*' => Http::response([], 204)]);

    expect(OlxApi::usersBusiness()->deleteBanner(7))->toBeTrue();

    Http::assertSent(fn (Request $request): bool => $request->method() === 'DELETE'
        && $request->url() === 'https://www.olx.test/api/open/users-business/me/banners/7');
});
