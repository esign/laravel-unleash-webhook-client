<?php

namespace Esign\UnleashWebhookClient\DataTransferObjects;

use Spatie\LaravelData\Data;

class UnleashWebhookEntry extends Data
{
    public function __construct(
        public array $ids,
        public string $type,
        public string $table,
    ) {
    }
}
