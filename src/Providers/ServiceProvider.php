<?php

namespace Presspack\Framework\Providers;

use Illuminate\Support\Str;
use Presspack\Framework\Post;
use Presspack\Framework\Support\Translation\Strings;
use Presspack\Framework\Support\Localization\Localize;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Presspack\Framework\Support\Facades\Strings as StringFacade;

class ServiceProvider extends BaseServiceProvider
{
    protected $commands = [
        'Presspack\Framework\Commands\SaltsGenerate',
        'Presspack\Framework\Commands\MakeCustomPostType',
    ];

    public $singletons = [
        'presspack/localize' => Localize::class,
        'presspack/strings' => Strings::class,
    ];
    public $facades = [
        'presspack/post' => Post::class,
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
            Str::macro('get', function (string $string) {
                return StringFacade::get($string);
            });
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->commands($this->commands);

        $this->bindFacades();
        $this->bindSingletons();

        $this->mergeConfigFrom(__DIR__.'/../../config/presspack.php', 'presspack');
    }

    public function bindFacades()
    {
        foreach ($this->facades as $accessor => $class) {
            $this->app->bind($accessor, function ($class) {
                return new $class();
            });
        }
    }

    public function bindSingletons()
    {
        foreach ($this->singletons as $accessor => $class) {
            $this->app->singleton($accessor, function ($class) {
                return new $class();
            });
        }
    }
}
