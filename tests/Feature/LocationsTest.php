<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Sashalenz\OlxApi\OlxApi;

it('lists cities and a city districts', function (): void {
    Http::fake([
        '*/cities/5/districts' => Http::response(['data' => [['id' => 50, 'name' => 'Шевченківський', 'city_id' => 5]]], 200),
        '*/cities*' => Http::response(['data' => [['id' => 5, 'name' => 'Київ', 'region_id' => 1]]], 200),
    ]);

    expect(OlxApi::cities()->all()->data[0]->name)->toBe('Київ')
        ->and(OlxApi::cities()->districts(5)->data[0]->name)->toBe('Шевченківський');
});

it('gets a single city', function (): void {
    Http::fake(['*' => Http::response(['data' => ['id' => 5, 'name' => 'Київ']], 200)]);

    expect(OlxApi::cities()->get(5)->id)->toBe(5);
});

it('lists regions and gets one', function (): void {
    Http::fake([
        '*/regions/1' => Http::response(['data' => ['id' => 1, 'name' => 'Київська область']], 200),
        '*/regions' => Http::response(['data' => [['id' => 1, 'name' => 'Київська область']]], 200),
    ]);

    expect(OlxApi::regions()->all()->data[0]->id)->toBe(1)
        ->and(OlxApi::regions()->get(1)->name)->toBe('Київська область');
});

it('lists districts', function (): void {
    Http::fake(['*' => Http::response(['data' => [['id' => 50, 'name' => 'Шевченківський']]], 200)]);

    expect(OlxApi::districts()->all()->data[0]->id)->toBe(50);
});

it('reverse-geocodes coordinates to a location', function (): void {
    Http::fake(['*' => Http::response(['data' => ['city' => ['id' => 5, 'name' => 'Київ'], 'district' => ['id' => 50]]], 200)]);

    $location = OlxApi::locations()->byCoordinates(50.45, 30.52);

    expect($location->city?->name)->toBe('Київ')
        ->and($location->district?->id)->toBe(50);

    Http::assertSent(fn (Request $request): bool => str_starts_with($request->url(), 'https://www.olx.test/api/open/locations?')
        && (string) $request['latitude'] === '50.45');
});

it('lists currencies and languages', function (): void {
    Http::fake([
        '*/currencies' => Http::response(['data' => [['code' => 'UAH', 'label' => 'Гривня']]], 200),
        '*/languages' => Http::response(['data' => [['code' => 'uk', 'name' => 'Українська']]], 200),
    ]);

    expect(OlxApi::currencies()->all()->data[0]->code)->toBe('UAH')
        ->and(OlxApi::languages()->all()->data[0]->code)->toBe('uk');
});
