<?php

namespace lirui\member;

class MemberServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //publish相关文件
        $this->publishes([
            __DIR__ . '/config/memberModule.php' => config_path('memberModule.php'),
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/migrations');

        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}