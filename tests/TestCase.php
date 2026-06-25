<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Sashalenz\OlxApi\OlxApiServiceProvider;
use Spatie\LaravelData\LaravelDataServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelDataServiceProvider::class,
            OlxApiServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('olx-api.base_url', 'https://www.olx.test');
        $app['config']->set('olx-api.api_url', 'https://www.olx.test/api/partner');
        $app['config']->set('olx-api.client_id', 'client-id');
        $app['config']->set('olx-api.client_secret', 'client-secret');
        $app['config']->set('olx-api.redirect_uri', 'https://a20.test/olx/callback');
        $app['config']->set('olx-api.token', 'test-token');
        $app['config']->set('olx-api.scope', 'v2 read write');
        $app['config']->set('olx-api.locale', 'uk');
        $app['config']->set('olx-api.version', '2.0');
    }
}
