<?php

namespace App\Providers;

use Overtrue\EasySms\EasySms;
use Illuminate\Support\ServiceProvider;

class EasySmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     * 在 register 方法中， 你只需要将事物绑定到服务容器。
     * 而不要尝试在 register 方法中注册任何监听器，路由，或者其他任何功能
     * @return void
     */
    public function register()
    {
        $this->app->singleton(EasySms::class, function ($app) {
            return new EasySms(config('easysms'));
        });

        $this->app->alias(EasySms::class, 'easysms');
    }

    /**
     * Bootstrap services.
     * 该方法在所有服务提供者被注册以后才会被调用， 这就是说我们可以在其中访问框架已注册的所有其它服务。
     * @return void
     */
    public function boot()
    {
        //
    }
}
