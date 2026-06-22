<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Sashalenz\OlxApi\OlxApi;

it('lists threads with unread counts', function (): void {
    Http::fake(['*' => Http::response([
        'data' => [
            ['id' => 10, 'advert_id' => 1, 'interlocutor_id' => 555, 'unread_count' => 2],
        ],
        'metadata' => ['total_elements' => 1],
    ], 200)]);

    $threads = OlxApi::threads()->all(['limit' => 50]);

    expect($threads->data[0]->id)->toBe(10)
        ->and($threads->data[0]->interlocutorId)->toBe(555)
        ->and($threads->data[0]->unreadCount)->toBe(2);

    Http::assertSent(fn (Request $request): bool => $request->method() === 'GET'
        && str_starts_with($request->url(), 'https://www.olx.test/api/open/threads?'));
});

it('gets a single thread', function (): void {
    Http::fake(['*' => Http::response(['data' => ['id' => 10, 'advert_id' => 1]], 200)]);

    expect(OlxApi::threads()->get(10)->id)->toBe(10);
});

it('marks a thread as read', function (): void {
    Http::fake(['*' => Http::response([], 200)]);

    expect(OlxApi::threads()->markAsRead(10))->toBeTrue();

    Http::assertSent(fn (Request $request): bool => $request->method() === 'POST'
        && $request->url() === 'https://www.olx.test/api/open/threads/10/commands'
        && $request['command'] === 'mark-as-read');
});

it('sets a thread as favourite', function (): void {
    Http::fake(['*' => Http::response([], 200)]);

    OlxApi::threads()->setFavourite(10);

    Http::assertSent(fn (Request $request): bool => $request['command'] === 'set-favourite');
});
