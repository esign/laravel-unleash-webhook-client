<?php

namespace Esign\UnleashWebhookClient\Tests;

use Esign\UnleashWebhookClient\DataTransferObjects\UnleashWebhookEntry;
use Esign\UnleashWebhookClient\Tests\Support\RecordingProcessUnleashWebhookJob;
use Esign\UnleashWebhookClient\Testing\MocksUnleashWebhooks;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;

final class UnleashWebhookClientTest extends TestCase
{
    use MocksUnleashWebhooks;

    #[Test]
    public function it_can_dispatch_a_webhook_job(): void
    {
        // Arrange
        Queue::fake();

        // Act
        $response = $this->makeUnleashWebhookRequestFromFixture(
            fixtureFilename: 'translations-updated.json',
            endpoint: '/unleash-webhook'
        );

        // Assert
        Queue::assertPushed(RecordingProcessUnleashWebhookJob::class);
        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_cast_the_webhook_entry_to_a_dto(): void
    {
        // Arrange
        RecordingProcessUnleashWebhookJob::$handledEntries = [];

        // Act
        $this->makeUnleashWebhookRequestFromFixture(
            fixtureFilename: 'translations-updated.json',
            endpoint: 'unleash-webhook'
        );

        // Assert
        $this->assertInstanceOf(UnleashWebhookEntry::class, RecordingProcessUnleashWebhookJob::$handledEntries[0]);
        $this->assertEquals([3968], RecordingProcessUnleashWebhookJob::$handledEntries[0]->ids);
        $this->assertEquals('updated', RecordingProcessUnleashWebhookJob::$handledEntries[0]->type);
        $this->assertEquals('translations', RecordingProcessUnleashWebhookJob::$handledEntries[0]->table);
    }
}