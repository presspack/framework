<?php

namespace Presspack\Framework\Providers;

use Illuminate\Support\Str;
use Presspack\Framework\Post;
use Presspack\Framework\Support\Localize;
use Presspack\Framework\Support\Translation\Strings;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    protected $commands = [
        'Presspack\Framework\Commands\SaltsGenerate',
        'Presspack\Framework\Commands\MakeCustomPostType',
    ];

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/presspack.php' => config_path('presspack.php'),
            ], 'config');
        }

        if (config('presspack.i18n')) {
            Str::macro('get', function (string $string, string $locale = null) {
                return (new Strings())->get($string, $locale);
            });
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->commands($this->commands);

        $this->app->bind('Presspack\Framework\Post', function ($app) {
            return new Post();
        });

        $this->app->singleton('Presspack\Framework\Localize', function ($app) {
            return new Localize();
        });

        $this->mergeConfigFrom(__DIR__.'/../../config/presspack.php', 'presspack');
    }
}
