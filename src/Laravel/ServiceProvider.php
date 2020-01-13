<?php

namespace Jason\BlockChain\Laravel;

use Jason\BlockChain\BlockChain;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../../config/config.php' => config_path('block_chain.php')], 'block_chain');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'block_chain');
        $config = config('block_chain');

        $this->app->singleton('block_chain', function ($app) use ($config) {
            return new BlockChain($config);
        });
    }

    public function provides()
    {
        return [BlockChain::class];
    }

}
