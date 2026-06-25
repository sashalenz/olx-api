<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Sashalenz\OlxApi\Enums\AdvertStatus;
use Sashalenz\OlxApi\OlxApi;

it('lists adverts and hydrates DTOs from the data envelope', function (): void {
    Http::fake(['*' => Http::response([
        'data' => [
            ['id' => 1, 'status' => 'active', 'title' => 'Фара Jeep'],
            ['id' => 2, 'status' => 'outdated', 'title' => 'Двигун'],
        ],
        'metadata' => ['total_elements' => 2],
    ], 200)]);

    $result = OlxApi::adverts()->all(['limit' => 50]);

    expect($result->count())->toBe(2)
        ->and($result->totalCount())->toBe(2)
        ->and($result->data[0]->id)->toBe(1)
        ->and($result->data[0]->statusEnum())->toBe(AdvertStatus::Active);

    Http::assertSent(fn (Request $request): bool => $request->method() === 'GET'
        && str_starts_with($request->url(), 'https://www.olx.test/api/partner/adverts?')
        && $request->hasHeader('Authorization', 'Bearer test-token')
        && $request->hasHeader('Version', '2.0')
        && $request->hasHeader('Accept-Language', 'uk'));
});

it('gets a single advert', function (): void {
    Http::fake(['*' => Http::response(['data' => ['id' => 7, 'title' => 'X', 'images' => [['url' => 'https://i/1.jpg']]]], 200)]);

    $advert = OlxApi::adverts()->get(7);

    expect($advert->id)->toBe(7)
        ->and($advert->images[0]->url)->toBe('https://i/1.jpg');

    Http::assertSent(fn (Request $request): bool => $request->url() === 'https://www.olx.test/api/partner/adverts/7');
});

it('creates an advert', function (): void {
    Http::fake(['*' => Http::response(['data' => ['id' => 99, 'status' => 'new']], 200)]);

    $advert = OlxApi::adverts()->create(['title' => 'Двигун 2.0', 'category_id' => 1234]);

    expect($advert->id)->toBe(99)
        ->and($advert->statusEnum())->toBe(AdvertStatus::New);

    Http::assertSent(fn (Request $request): bool => $request->method() === 'POST'
        && $request->url() === 'https://www.olx.test/api/partner/adverts'
        && $request['title'] === 'Двигун 2.0'
        && $request['category_id'] === 1234);
});

it('updates an advert', function (): void {
    Http::fake(['*' => Http::response(['data' => ['id' => 5]], 200)]);

    OlxApi::adverts()->update(5, ['price' => ['value' => 1000, 'currency' => 'UAH']]);

    Http::assertSent(fn (Request $request): bool => $request->method() === 'PUT'
        && $request->url() === 'https://www.olx.test/api/partner/adverts/5');
});

it('deletes an advert', function (): void {
    Http::fake(['*' => Http::response([], 204)]);

    expect(OlxApi::adverts()->delete(5))->toBeTrue();

    Http::assertSent(fn (Request $request): bool => $request->method() === 'DELETE'
        && $request->url() === 'https://www.olx.test/api/partner/adverts/5');
});

it('runs lifecycle commands', function (): void {
    Http::fake(['*' => Http::response([], 200)]);

    OlxApi::adverts()->activate(5);
    OlxApi::adverts()->deactivate(5, isSuccess: true);
    OlxApi::adverts()->finish(5);
    OlxApi::adverts()->extend(5);

    Http::assertSent(fn (Request $request): bool => $request->url() === 'https://www.olx.test/api/partner/adverts/5/commands'
        && $request['command'] === 'activate');
    Http::assertSent(fn (Request $request): bool => $request['command'] === 'deactivate'
        && $request['is_success'] === true);
    Http::assertSent(fn (Request $request): bool => $request['command'] === 'finish');
    Http::assertSent(fn (Request $request): bool => $request['command'] === 'extend');
});

it('reads advert statistics', function (): void {
    Http::fake(['*' => Http::response(['data' => ['views' => 120, 'phone_views' => 8, 'favourites' => 3]], 200)]);

    $stats = OlxApi::adverts()->statistics(5);

    expect($stats->views)->toBe(120)
        ->and($stats->phoneViews)->toBe(8)
        ->and($stats->favourites)->toBe(3);
});

it('lists and buys paid features for an advert', function (): void {
    Http::fake([
        '*/paid-features' => Http::response(['data' => [['type' => 'top', 'name' => 'Топ', 'price' => 50, 'currency' => 'UAH']]], 200),
    ]);

    $features = OlxApi::adverts()->paidFeatures(5);

    expect($features->data[0]->type)->toBe('top');

    OlxApi::adverts()->buyPaidFeature(5, ['type' => 'top', 'period' => 7]);

    Http::assertSent(fn (Request $request): bool => $request->method() === 'POST'
        && $request->url() === 'https://www.olx.test/api/partner/adverts/5/paid-features'
        && $request['type'] === 'top');
});

it('uses a per-account token override', function (): void {
    Http::fake(['*' => Http::response(['data' => []], 200)]);

    OlxApi::adverts()->token('account-b-token')->all();

    Http::assertSent(fn (Request $request): bool => $request->hasHeader('Authorization', 'Bearer account-b-token'));
});
