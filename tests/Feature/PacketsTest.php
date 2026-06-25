<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Sashalenz\OlxApi\OlxApi;

it('lists available packets', function (): void {
    Http::fake(['*' => Http::response(['data' => [['id' => 1, 'type' => 'mega', 'size' => 200, 'price' => 1500, 'currency' => 'UAH']]], 200)]);

    $packets = OlxApi::packets()->all(['category_id' => 1234]);

    expect($packets->data[0]->type)->toBe('mega')
        ->and($packets->data[0]->size)->toBe(200);
});

it('lists zones for a category', function (): void {
    Http::fake(['*' => Http::response(['data' => [['id' => 3, 'name' => 'Київ']]], 200)]);

    OlxApi::packets()->zones(['category_id' => 1234]);

    Http::assertSent(fn (Request $request): bool => str_starts_with($request->url(), 'https://www.olx.test/api/partner/zones?')
        && $request['category_id'] === 1234);
});

it('lists user packets and buys one', function (): void {
    Http::fake([
        '*/users/me/packets' => Http::response(['data' => [['id' => 9, 'name' => 'Мега', 'available' => 50]]], 200),
    ]);

    expect(OlxApi::packets()->userPackets()->data[0]->available)->toBe(50);

    OlxApi::packets()->buyForUser(['category_id' => 1234, 'size' => 200, 'payment_method' => 'account']);

    Http::assertSent(fn (Request $request): bool => $request->method() === 'POST'
        && $request->url() === 'https://www.olx.test/api/partner/users/me/packets'
        && $request['size'] === 200);
});

it('lists the paid-features catalog', function (): void {
    Http::fake(['*' => Http::response(['data' => [['type' => 'vip', 'name' => 'VIP', 'price' => 100, 'currency' => 'UAH']]], 200)]);

    expect(OlxApi::paidFeatures()->all()->data[0]->type)->toBe('vip');
});
