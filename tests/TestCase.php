<?php

namespace Esign\UnleashWebhookClient\Tests;

use Esign\UnleashWebhookClient\UnleashWebhookClientServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [UnleashWebhookClientServiceProvider::class];
    }
} 