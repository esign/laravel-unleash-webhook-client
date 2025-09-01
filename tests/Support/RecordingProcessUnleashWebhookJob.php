<?php

namespace Esign\UnleashWebhookClient\Tests\Support;

use Esign\UnleashWebhookClient\Jobs\ProcessUnleashWebhookJob;

class RecordingProcessUnleashWebhookJob extends ProcessUnleashWebhookJob
{
    public static array $handledEntries = [];

    public function handle(): void
    {
        foreach ($this->getWebhookEntries() as $entry) {
            self::$handledEntries[] = $entry;
        }
    }
}
