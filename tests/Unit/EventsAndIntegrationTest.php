<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit;

use KaririCode\Transformer\Event\TransformationCompletedEvent;
use KaririCode\Transformer\Event\TransformationStartedEvent;
use KaririCode\Transformer\Integration\ProcessorBridge;
use KaririCode\Transformer\Provider\TransformerServiceProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(TransformationStartedEvent::class)]
#[CoversClass(TransformationCompletedEvent::class)]
#[CoversClass(ProcessorBridge::class)]
final class EventsAndIntegrationTest extends TestCase
{
    #[Test]
    public function testTransformationStartedEvent(): void
    {
        $event = new TransformationStartedEvent(['name', 'price'], 1234567890.0);

        $this->assertSame(['name', 'price'], $event->fields);
        $this->assertSame(1234567890.0, $event->timestamp);
    }

    #[Test]
    public function testTransformationStartedEventDefaultTimestamp(): void
    {
        $event = new TransformationStartedEvent(['field']);

        $this->assertSame(['field'], $event->fields);
        $this->assertSame(0.0, $event->timestamp);
    }

    #[Test]
    public function testTransformationCompletedEvent(): void
    {
        $engine = new TransformerServiceProvider()->createEngine();
        $result = $engine->transform(['x' => 'hello'], ['x' => ['camel_case']]);

        $event = new TransformationCompletedEvent($result, 12.5, 1234567890.0);

        $this->assertSame($result, $event->result);
        $this->assertSame(12.5, $event->durationMs);
        $this->assertSame(1234567890.0, $event->timestamp);
    }

    #[Test]
    public function testTransformationCompletedEventDefaultTimestamp(): void
    {
        $engine = new TransformerServiceProvider()->createEngine();
        $result = $engine->transform(['x' => 'hello'], []);

        $event = new TransformationCompletedEvent($result, 5.0);

        $this->assertSame(0.0, $event->timestamp);
    }

    #[Test]
    public function testProcessorBridgeProcess(): void
    {
        $engine = new TransformerServiceProvider()->createEngine();
        $bridge = new ProcessorBridge($engine, ['name' => ['camel_case']]);

        $output = $bridge->process(['name' => 'hello_world']);

        $this->assertArrayHasKey('data', $output);
        $this->assertArrayHasKey('result', $output);
        $this->assertSame('helloWorld', $output['data']['name']);
    }
}
