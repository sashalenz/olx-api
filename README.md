# olx-api

OLX.ua **Partner API v2** SDK for Laravel — a thin, typed client for the
marketplace publisher + chat intake use cases. Mirrors the structure of
`sashalenz/chatwoot-api` (static entry class, fluent resources, `spatie/laravel-data`
DTOs, `Http`-facade transport).

> Scope: the **Partner API v2** served at `https://www.olx.ua/api/open/…` —
> adverts, chat threads/messages, categories, locations, packets, paid features,
> users and OAuth. Push (webhooks) and `read:leads` (call leads) live on the
> separate OLX Group PartnerHub program and are **not** part of this API.

## Install

```bash
composer require sashalenz/olx-api
```

```bash
php artisan vendor:publish --tag=olx-api-config
```

```dotenv
OLX_API_BASE_URL=https://www.olx.ua
OLX_API_CLIENT_ID=...
OLX_API_CLIENT_SECRET=...
OLX_API_REDIRECT_URI=https://your-app/olx/callback
# single-account convenience default (multi-account passes ->token() per call):
OLX_API_TOKEN=
```

## OAuth (per-account onboarding)

The SDK is **stateless** about token storage — minting/refresh is here, but
persisting the access + refresh tokens (and refreshing before the ~1-month
refresh-token lapses) is the consumer's job.

```php
use Sashalenz\OlxApi\OlxApi;

// 1. redirect the manager to consent
$url = OlxApi::oauth()->authorizeUrl(state: 'account-b');

// 2. on the callback, exchange the code → persist the token
$token = OlxApi::oauth()->exchangeCode($request->query('code'));
// $token->accessToken, $token->refreshToken, $token->expiresIn

// 3. later, refresh (OLX rotates the refresh token — persist the new one)
$token = OlxApi::oauth()->refresh($storedRefreshToken);
```

## Per-account calls

Five manager accounts (one per FOP) each carry their own access token; pass it
at call time:

```php
$threads = OlxApi::threads()->token($accessB)->all(['limit' => 50]);

foreach ($threads->data as $thread) {
    if ($thread->unreadCount > 0) {
        $messages = OlxApi::messages()->token($accessB)->all($thread->id);
        OlxApi::threads()->token($accessB)->markAsRead($thread->id);
    }
}

// reply (attachments are pre-hosted URLs; no upload endpoint)
OlxApi::messages()->token($accessB)->post($threadId, 'Доброго дня! Так, є в наявності.');
```

## Publishing adverts

```php
$advert = OlxApi::adverts()->token($accessB)->create([
    'title' => 'Фара права Jeep Grand Cherokee WK2',
    'description' => '...80–9000 chars, no phone numbers...',
    'category_id' => 1234,                 // leaf category
    'advertiser_type' => 'business',
    'contact' => ['name' => 'А20', 'phone' => '+380...'],
    'location' => ['city_id' => 5],
    'price' => ['value' => 3500, 'currency' => 'UAH', 'negotiable' => true],
    'images' => [['url' => 'https://cdn.a20/part-1.jpg']],
    'attributes' => [['code' => 'make', 'value' => 'jeep']],
]);

OlxApi::adverts()->token($accessB)->finish($advert->id);   // sold
```

## Resources

`oauth()` · `adverts()` · `threads()` · `messages()` · `categories()` ·
`cities()` · `regions()` · `districts()` · `locations()` · `currencies()` ·
`languages()` · `packets()` · `paidFeatures()` · `users()` · `usersBusiness()`

## Errors

All failures throw an `OlxApiException` subclass carrying the parsed `error`
envelope (`status`, `title`, `detail`, `validation`):

`ValidationException` (400/422) · `UnauthorizedException` (401) ·
`ForbiddenException` (403) · `NotFoundException` (404) ·
`RateLimitException` (429) · `ServerException` (5xx).

## Testing

```bash
composer test
composer analyse
composer format
```

## License

MIT.
