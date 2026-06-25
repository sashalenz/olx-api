<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Sashalenz\OlxApi\OlxApi;

it('lists categories', function (): void {
    Http::fake(['*' => Http::response(['data' => [['id' => 1, 'name' => 'Запчастини', 'photos_limit' => 8]]], 200)]);

    $categories = OlxApi::categories()->all(['parent_id' => 0]);

    expect($categories->data[0]->name)->toBe('Запчастини')
        ->and($categories->data[0]->photosLimit)->toBe(8);

    Http::assertSent(fn (Request $request): bool => str_starts_with($request->url(), 'https://www.olx.test/api/partner/categories?'));
});

it('gets a single category', function (): void {
    Http::fake(['*' => Http::response(['data' => ['id' => 1234, 'name' => 'Фари']], 200)]);

    expect(OlxApi::categories()->get(1234)->id)->toBe(1234);
});

it('lists category attributes', function (): void {
    Http::fake(['*' => Http::response(['data' => [
        ['code' => 'make', 'label' => 'Марка', 'validation' => ['required' => true], 'values' => [['code' => 'jeep', 'label' => 'Jeep']]],
    ]], 200)]);

    $attrs = OlxApi::categories()->attributes(1234);

    expect($attrs->data[0]->code)->toBe('make')
        ->and($attrs->data[0]->validation['required'])->toBeTrue()
        ->and($attrs->data[0]->values[0]['code'])->toBe('jeep');

    Http::assertSent(fn (Request $request): bool => $request->url() === 'https://www.olx.test/api/partner/categories/1234/attributes');
});

it('suggests a category by query', function (): void {
    Http::fake(['*' => Http::response(['data' => [['id' => 9, 'name' => 'Фари']]], 200)]);

    OlxApi::categories()->suggestion('фара jeep');

    Http::assertSent(fn (Request $request): bool => str_starts_with($request->url(), 'https://www.olx.test/api/partner/categories/suggestion?')
        && $request['q'] === 'фара jeep');
});
