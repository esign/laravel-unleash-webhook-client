<?php

namespace Esign\UnleashWebhookClient\Jobs;

use Esign\UnleashWebhookClient\DataTransferObjects\UnleashWebhookEntry;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

class ProcessUnleashWebhookJob extends ProcessWebhookJob
{
    /**
     * @return \Esign\UnleashWebhookClient\DataTransferObjects\UnleashWebhookEntry[]
     */
    protected function getWebhookEntries(): array
    {
        // Unleash sends out webhooks using a wrapper object.
        // [{"9044":{"ids":[3968],"type":"updated","table":"article_translations"}}]
        // We do not want this wrapper object, since we can't use integers as variable names in PHP classes.
        // Let's remove this wrapper object and covert it to a structure that we can actually use.
        // [{"ids":[3968],"type":"updated","table":"article_translations"}]`
        $webhookPayloadWithoutWrappingObjects = collect($this->webhookCall->payload)
            ->flatten(1)
            ->toArray();

        return array_map(
            fn (array $webhookEntry) => UnleashWebhookEntry::from($webhookEntry),
            $webhookPayloadWithoutWrappingObjects,
        );
    }
}
