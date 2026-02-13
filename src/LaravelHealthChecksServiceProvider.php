<?php

namespace Code16\LaravelHealthChecks;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Code16\LaravelHealthChecks\Commands\LaravelHealthChecksCommand;

class LaravelHealthChecksServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-health-checks');
    }
}
