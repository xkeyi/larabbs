<?php

namespace App\Providers;

use App\Models\Topic;
use App\Observers\TopicObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // if (config('app.debug')) {
        if (app()->isLocal()) {
            $this->app->register('VIACreative\SudoSu\ServiceProvider');
        }

        \API::error(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception) {
            // abort(404, '404 Not Found');
            throw new \Symfony\Component\HttpKernel\Exception\HttpException(404, '404 Not Found');
        });

        \API::error(function (\Illuminate\Auth\Access\AuthorizationException $exception) {
            abort(403, $exception->getMessage());
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Topic::observe(TopicObserver::class);
    }
}
