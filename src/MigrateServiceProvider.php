<?php

namespace Gouguoyin\MigrationsCreator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

class MigrateServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MigrateCommand::class,
            ]);
        }
    }

    public function provides()
    {
        return [
            MigrateCommand::class,
        ];
    }

}