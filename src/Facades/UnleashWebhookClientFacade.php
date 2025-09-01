<?php

namespace Esign\UnleashWebhookClient\Facades;

use Illuminate\Support\Facades\Facade;

class UnleashWebhookClientFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'unleash-webhook-client';
    }
}
