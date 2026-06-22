<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi;

use Sashalenz\OlxApi\ApiModels\Adverts;
use Sashalenz\OlxApi\ApiModels\Categories;
use Sashalenz\OlxApi\ApiModels\Cities;
use Sashalenz\OlxApi\ApiModels\Currencies;
use Sashalenz\OlxApi\ApiModels\Districts;
use Sashalenz\OlxApi\ApiModels\Languages;
use Sashalenz\OlxApi\ApiModels\Locations;
use Sashalenz\OlxApi\ApiModels\Messages;
use Sashalenz\OlxApi\ApiModels\OAuth;
use Sashalenz\OlxApi\ApiModels\Packets;
use Sashalenz\OlxApi\ApiModels\PaidFeatures;
use Sashalenz\OlxApi\ApiModels\Regions;
use Sashalenz\OlxApi\ApiModels\Threads;
use Sashalenz\OlxApi\ApiModels\Users;
use Sashalenz\OlxApi\ApiModels\UsersBusiness;

/**
 * Static entrypoint for the OLX Partner API v2, mirroring `ChatwootApi` /
 * `ViberBotApi`.
 *
 *   // OAuth onboarding (per manager account)
 *   $url   = OlxApi::oauth()->authorizeUrl(state: 'account-b');
 *   $token = OlxApi::oauth()->exchangeCode($code);          // persist token + refresh
 *
 *   // Per-account calls — pass the stored access token
 *   $threads = OlxApi::threads()->token($accessB)->all(['limit' => 50]);
 *   OlxApi::messages()->token($accessB)->post($threadId, 'Доброго дня!');
 *   OlxApi::adverts()->token($accessB)->create([...]);
 *
 * Single-account consumers can instead set `olx-api.token` and drop ->token().
 */
class OlxApi
{
    public static function oauth(): OAuth
    {
        return new OAuth;
    }

    public static function adverts(): Adverts
    {
        return new Adverts;
    }

    public static function threads(): Threads
    {
        return new Threads;
    }

    public static function messages(): Messages
    {
        return new Messages;
    }

    public static function categories(): Categories
    {
        return new Categories;
    }

    public static function cities(): Cities
    {
        return new Cities;
    }

    public static function regions(): Regions
    {
        return new Regions;
    }

    public static function districts(): Districts
    {
        return new Districts;
    }

    public static function locations(): Locations
    {
        return new Locations;
    }

    public static function currencies(): Currencies
    {
        return new Currencies;
    }

    public static function languages(): Languages
    {
        return new Languages;
    }

    public static function packets(): Packets
    {
        return new Packets;
    }

    public static function paidFeatures(): PaidFeatures
    {
        return new PaidFeatures;
    }

    public static function users(): Users
    {
        return new Users;
    }

    public static function usersBusiness(): UsersBusiness
    {
        return new UsersBusiness;
    }
}
