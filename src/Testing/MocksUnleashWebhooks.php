<?php

namespace Esign\UnleashWebhookClient\Testing;

use Illuminate\Support\Facades\File;
use Illuminate\Testing\TestResponse;
use Spatie\WebhookClient\WebhookConfig;
use Spatie\WebhookClient\WebhookConfigRepository;

trait MocksUnleashWebhooks
{
    protected string $unleashWebhookConfigurationName = 'default';

    protected ?string $unleashWebhookFixturePath = null;

    public function usingUnleashWebhookConfigurationName(string $name): self
    {
        $this->unleashWebhookConfigurationName = $name;

        return $this;
    }

    public function usingUnleashWebhookFixturePath(?string $path): self
    {
        $this->unleashWebhookFixturePath = $path;

        return $this;
    }

    protected function getUnleashWebhookRequestFixture(string $fixtureFilename): string
    {
        $pathPrefix = $this->unleashWebhookFixturePath ?? base_path('tests/Fixtures/UnleashWebhooks/');

        return File::get($pathPrefix . $fixtureFilename);
    }

    protected function determineSignature(array $payload): string
    {
        /** @var WebhookConfig */
        $webhookConfig = app(WebhookConfigRepository::class)->getConfig($this->unleashWebhookConfigurationName);

        return hash_hmac('sha256', json_encode($payload), $webhookConfig->signingSecret);
    }

    public function makeUnleashWebhookRequestFromFixture(
        string $fixtureFilename,
        string $endpoint = '/api/unleash-webhook',
        string $method = 'POST',
        array $headers = [],
    ): TestResponse {
        $requestStub = $this->getUnleashWebhookRequestFixture($fixtureFilename);
        $payload = json_decode($requestStub, true);

        return $this->makeUnleashWebhookRequest(
            payload: $payload,
            endpoint: $endpoint,
            method: $method,
            headers: $headers,
        );
    }

    public function makeUnleashWebhookRequest(
        array $payload,
        string $endpoint = '/api/unleash-webhook',
        string $method = 'POST',
        array $headers = [],
    ): TestResponse {
        $headers = [
            'Signature' => $this->determineSignature($payload),
            ...$headers,
        ];

        return $this->json(
            $method,
            $endpoint,
            $payload,
            $headers
        );
    }
}