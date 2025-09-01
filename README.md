# Receive Unleash webhooks within a Laravel application

[![Latest Version on Packagist](https://img.shields.io/packagist/v/esign/laravel-unleash-webhook-client.svg?style=flat-square)](https://packagist.org/packages/esign/laravel-unleash-webhook-client)
[![Total Downloads](https://img.shields.io/packagist/dt/esign/laravel-unleash-webhook-client.svg?style=flat-square)](https://packagist.org/packages/esign/laravel-unleash-webhook-client)
![GitHub Actions](https://github.com/esign/laravel-unleash-webhook-client/actions/workflows/main.yml/badge.svg)

This package builds on top of [Spatie's Laravel Webhook Client package](https://github.com/spatie/laravel-webhook-client) to provide handling of [Unleash](https://www.unleash.be) webhooks in Laravel applications.

## Installation

You can install the package via composer:

```bash
composer require esign/laravel-unleash-webhook-client
```

## Configuration
You may follow the steps under the [configuration section](https://github.com/spatie/laravel-webhook-client#configuring-the-package) of Spatie's Laravel Webhook Client package.

You can find the `WEBHOOK_CLIENT_SECRET` in the website's configuration section of Unleash.  
To set up a webhook, register a new webhook under the Webhooks section for the desired module.  
Set the webhook URL to the route you have configured in your Laravel application, for example: `Route::webhooks('unleash-webhook');`

## Processing webhooks
To process the webhook, extend the `Esign\UnleashWebhookClient\Jobs\ProcessUnleashWebhookJob` job in your application.  
You can use its `getWebhookEntries` method to extract the relevant data from the webhook payload and process it as needed.
```php
namespace App\Jobs;

use App\Models\Redirect;
use App\Models\Translation;
use Esign\UnleashWebhookClient\Jobs\ProcessUnleashWebhookJob as BaseProcessUnleashWebhookJob;

class ProcessUnleashWebhookJob extends BaseProcessUnleashWebhookJob
{
    public function handle(): void
    {
        foreach ($this->getWebhookEntries() as $webhookEntry) {
            match ($webhookEntry->table) {
                (new Translation)->getTable() => $this->handleTranslationsWebhook($webhookEntry),
                (new Redirect)->getTable() => $this->handleRedirectsWebhook($webhookEntry),
                default => null,
            };
        }
    }

    protected function handleTranslationsWebhook(WebhookEntry $webhookEntry): void
    {
        // Handle the translation webhook entry
    }

    protected function handleRedirectsWebhook(WebhookEntry $webhookEntry): void
    {
        // Handle the redirects webhook entry
    }
}
```

## Mocking Unleash Webhooks in Tests
This package provides a `MocksUnleashWebhooks` trait to simplify testing Unleash webhook integrations in your Laravel application.
The trait allows you to easily send signed webhook requests using fixture files or custom payloads.

```php
use Esign\UnleashWebhookClient\Testing\MocksUnleashWebhooks;

class ExampleTest extends TestCase
{
    use MocksUnleashWebhooks;

    #[Test]
    public function it_can_process_unleash_webhooks(): void
    {
        $responseA = $this->makeUnleashWebhookRequest(['example' => 'data']);
        $responseB = $this->makeUnleashWebhookRequestFromFixture('example-webhook.json');

        $responseA->assertOk();
        $responseB->assertOk();
    }
}
```

By default, fixture files are expected to be located in the `tests/Fixtures/UnleashWebhooks/` directory.
You may provide a custom path for the fixture files using the `usingUnleashWebhookFixturePath(?string $path)` method.

## Running Tests

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
