<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class OlxApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('olx-api')
            ->hasConfigFile();
    }
}
