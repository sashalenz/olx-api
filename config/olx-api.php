<?php

declare(strict_types=1);

return [
    /*
     | OAuth host — where the consent + token endpoints live:
     |   {base_url}/oauth/authorize  and  {base_url}/api/open/oauth/token
     | (the `/oauth/authorize` + `/api/open/oauth/token` paths are added by the
     | SDK; do NOT include them here). This is intentionally SEPARATE from the
     | resource API base below — OAuth and the Partner API are different surfaces.
     */
    'base_url' => env('OLX_API_BASE_URL', 'https://www.olx.ua'),

    /*
     | Resource API base — the Partner API v2 root that every resource endpoint
     | (adverts, threads, users, …) hangs off. This is the FULL base including the
     | `/api/partner` segment, served from the www host. Per partner_api.yaml the
     | server is `https://www.olx.ua/api/partner`.
     */
    'api_url' => env('OLX_API_URL', 'https://www.olx.ua/api/partner'),

    /*
     | OAuth2 application credentials (issued at developer.olx.ua). `client_id`
     | and `client_secret` are used both for the `authorization_code` exchange
     | (per-manager account onboarding) and `client_credentials` (read-only
     | reference data: categories, cities, currencies). `redirect_uri` must match
     | the value registered for the app.
     */
    'client_id' => env('OLX_API_CLIENT_ID'),
    'client_secret' => env('OLX_API_CLIENT_SECRET'),
    'redirect_uri' => env('OLX_API_REDIRECT_URI'),

    /*
     | Default access token sent as `Authorization: Bearer …` on API calls. This
     | is the SINGLE-account convenience default; multi-account consumers (e.g.
     | five manager accounts, each on its own FOP) instead pass a per-account
     | token at call time via ->token($accessToken) on any resource. Token
     | persistence + refresh is the CONSUMER's job — the SDK stays stateless and
     | exposes OlxApi::oauth() to mint/refresh tokens.
     */
    'token' => env('OLX_API_TOKEN'),

    /*
     | OAuth scopes requested on the authorize step. The Partner API v2 uses the
     | space-delimited `v2 read write` triple: `read` covers adverts + threads +
     | statistics, `write` covers advert CRUD + chat replies.
     */
    'scope' => env('OLX_API_SCOPE', 'v2 read write'),

    /*
     | Language for `Accept-Language` (OLX is multilingual in Ukraine). Affects
     | localized fields in categories/attributes/cities responses.
     */
    'locale' => env('OLX_API_LOCALE', 'uk'),

    /*
     | API protocol version, sent as the mandatory `Version` header on every API
     | call. Partner API v2 requires `2.0`.
     */
    'version' => env('OLX_API_VERSION', '2.0'),
];
