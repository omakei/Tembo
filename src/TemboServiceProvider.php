<?php

namespace Omakei\Tembo;

use Omakei\Tembo\Commands\TemboCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TemboServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('tembo')
            ->hasConfigFile()
            ->hasViews()
            ->hasRoutes('tembo_api')
            ->hasMigration('create_tembo_table')
            ->hasCommand(TemboCommand::class);
    }
}
