<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Sashalenz\OlxApi\Enums\MessageType;
use Sashalenz\OlxApi\OlxApi;

it('lists messages of a thread and flags received', function (): void {
    Http::fake(['*' => Http::response([
        'data' => [
            ['id' => 1, 'thread_id' => 10, 'type' => 'received', 'text' => 'Є фара?', 'is_read' => false],
            ['id' => 2, 'thread_id' => 10, 'type' => 'sent', 'text' => 'Так, є'],
        ],
    ], 200)]);

    $messages = OlxApi::messages()->all(10);

    expect($messages->data[0]->isReceived())->toBeTrue()
        ->and($messages->data[0]->typeEnum())->toBe(MessageType::Received)
        ->and($messages->data[1]->isReceived())->toBeFalse();

    Http::assertSent(fn (Request $request): bool => $request->method() === 'GET'
        && $request->url() === 'https://www.olx.test/api/partner/threads/10/messages');
});

it('posts a reply', function (): void {
    Http::fake(['*' => Http::response(['data' => ['id' => 3, 'type' => 'sent', 'text' => 'Доброго дня']], 200)]);

    $message = OlxApi::messages()->post(10, 'Доброго дня');

    expect($message->id)->toBe(3);

    Http::assertSent(fn (Request $request): bool => $request->method() === 'POST'
        && $request->url() === 'https://www.olx.test/api/partner/threads/10/messages'
        && $request['text'] === 'Доброго дня'
        && ! array_key_exists('attachments', $request->data()));
});

it('posts a reply with pre-hosted attachments', function (): void {
    Http::fake(['*' => Http::response(['data' => ['id' => 4]], 200)]);

    OlxApi::messages()->post(10, 'Фото', [['url' => 'https://i/part.jpg']]);

    Http::assertSent(fn (Request $request): bool => $request['text'] === 'Фото'
        && $request['attachments'][0]['url'] === 'https://i/part.jpg');
});

it('gets a single message', function (): void {
    Http::fake(['*' => Http::response(['data' => ['id' => 1, 'thread_id' => 10]], 200)]);

    expect(OlxApi::messages()->get(10, 1)->id)->toBe(1);
});
