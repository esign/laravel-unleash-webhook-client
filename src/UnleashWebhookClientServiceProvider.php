<?php

namespace Esign\UnleashWebhookClient;

use Illuminate\Support\ServiceProvider;

class UnleashWebhookClientServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([$this->configPath() => config_path('unleash-webhook-client.php')], 'config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'unleash-webhook-client');

        $this->app->singleton('unleash-webhook-client', function () {
            return new UnleashWebhookClient;
        });
    }

    protected function configPath(): string
    {
        return __DIR__ . '/../config/unleash-webhook-client.php';
    }
}
