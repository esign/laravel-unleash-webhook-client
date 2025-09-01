<?php

namespace Esign\UnleashWebhookClient\Tests;

use Esign\UnleashWebhookClient\Testing\MocksUnleashWebhooks;
use Esign\UnleashWebhookClient\Tests\Support\RecordingProcessUnleashWebhookJob;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Spatie\LaravelData\LaravelDataServiceProvider;
use Spatie\WebhookClient\WebhookClientServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use MocksUnleashWebhooks;

    protected function getPackageProviders($app): array
    {
        return [
            LaravelDataServiceProvider::class,
            WebhookClientServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
        $this->setUpRoutes();
        $this->setUpConfig();
        $this->setUpUnleashWebhookMocking();
    }

    protected function setUpDatabase(): void
    {
        $migration = include __DIR__ . '/../vendor/spatie/laravel-webhook-client/database/migrations/create_webhook_calls_table.php.stub';

        $migration->up();
    }

    protected function setUpRoutes(): void
    {
        Route::webhooks('unleash-webhook');
    }

    protected function setUpConfig(): void
    {
        config()->set('webhook-client.configs.0.signing_secret', 'fake-signing-secret');
        config()->set('webhook-client.configs.0.process_webhook_job', RecordingProcessUnleashWebhookJob::class);
    }

    protected function setUpUnleashWebhookMocking(): void
    {
        RecordingProcessUnleashWebhookJob::$handledEntries = [];
        $this->usingUnleashWebhookFixturePath(__DIR__ . '/../tests/Fixtures/UnleashWebhooks/');
    }
} 