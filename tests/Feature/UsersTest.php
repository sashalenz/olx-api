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

    Http::assertSent(fn (Request $request): bool => $request->url() === 'https://www.olx.test/api/partner/users/me');
});

it('fetches a chat interlocutor (buyer) with name and avatar', function (): void {
    Http::fake(['*/users/15691775' => Http::response(['data' => ['id' => 15691775, 'name' => 'Олександр', 'avatar' => 'https://img.olx/avatar.jpg']], 200)]);

    $user = OlxApi::users()->get(15691775);

    expect($user->id)->toBe(15691775)
        ->and($user->name)->toBe('Олександр')
        ->and($user->avatar)->toBe('https://img.olx/avatar.jpg');

    Http::assertSent(fn (Request $request): bool => $request->url() === 'https://www.olx.test/api/partner/users/15691775');
});

it('reads account balance', function (): void {
    Http::fake(['*' => Http::response(['data' => ['sum' => 2380.23, 'wallet' => 2168.57, 'bonus' => 0, 'refund' => 211.66]], 200)]);

    $balance = OlxApi::users()->accountBalance();

    expect($balance->sum)->toBe(2380.23)
        ->and($balance->wallet)->toBe(2168.57)
        ->and($balance->refund)->toBe(211.66);
});

it('reads payment methods and billing', function (): void {
    Http::fake([
        '*/users/me/payment-methods' => Http::response(['data' => ['account']], 200),
        '*/users/me/billing*' => Http::response(['data' => [
            ['id' => 301896636, 'name' => "Пакет 30 оголошень 'Преміум'", 'date' => '2026-07-02 16:01:14', 'price' => '-2009.00', 'advert_id' => null],
            ['id' => 301009616, 'name' => 'Преміум оголошення', 'date' => '2026-06-18 12:59:09', 'price' => '-75.00', 'advert_id' => 911647316],
        ]], 200),
    ]);

    expect(OlxApi::users()->paymentMethods())->toBe(['account']);

    $billing = OlxApi::users()->billing(['limit' => 2]);

    expect($billing->data)->toHaveCount(2)
        ->and($billing->data[0]->price)->toBe('-2009.00')
        ->and($billing->data[0]->advertId)->toBeNull()
        ->and($billing->data[1]->advertId)->toBe(911647316);
});

it('lists prepaid and postpaid invoices', function (): void {
    Http::fake([
        '*/users/me/prepaid-invoices*' => Http::response(['data' => [['id' => 1]]], 200),
        '*/users/me/postpaid-invoices*' => Http::response(['data' => []], 200),
    ]);

    expect(OlxApi::users()->prepaidInvoices())->toBe([['id' => 1]])
        ->and(OlxApi::users()->postpaidInvoices(['page' => 1]))->toBe([]);

    Http::assertSent(fn (Request $request): bool => str_contains($request->url(), '/api/partner/users/me/prepaid-invoices'));
});

it('reads and updates the business profile', function (): void {
    Http::fake(['*' => Http::response(['data' => ['name' => 'А20', 'subdomain' => 'a20razborka']], 200)]);

    expect(OlxApi::usersBusiness()->me()->subdomain)->toBe('a20razborka');

    OlxApi::usersBusiness()->update(['description' => 'Авторозборка']);

    Http::assertSent(fn (Request $request): bool => $request->method() === 'PUT'
        && $request->url() === 'https://www.olx.test/api/partner/users-business/me'
        && $request['description'] === 'Авторозборка');
});

it('manages business banners', function (): void {
    Http::fake(['*' => Http::response([], 204)]);

    expect(OlxApi::usersBusiness()->deleteBanner(7))->toBeTrue();

    Http::assertSent(fn (Request $request): bool => $request->method() === 'DELETE'
        && $request->url() === 'https://www.olx.test/api/partner/users-business/me/banners/7');
});
