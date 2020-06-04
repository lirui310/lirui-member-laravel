<?php

namespace lirui\member;

use lirui\member\Commands\InitTable;
use lirui\member\Commands\ResetMemberTree;

class MemberServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/memberModule.php' => config_path('memberModule.php'),
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/migrations');

        $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InitTable::class,
                ResetMemberTree::class,
            ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/memberModule.php', 'memberModule'
        );
    }
}