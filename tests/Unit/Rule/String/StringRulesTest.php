<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\String;

use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Rule\String\{CamelCaseRule, KebabCaseRule, MaskRule, PascalCaseRule, RepeatRule, ReverseRule, SnakeCaseRule};
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(\KaririCode\Transformer\Rule\String\CamelCaseRule::class)]
#[CoversClass(\KaririCode\Transformer\Rule\String\SnakeCaseRule::class)]
#[CoversClass(\KaririCode\Transformer\Rule\String\KebabCaseRule::class)]
#[CoversClass(\KaririCode\Transformer\Rule\String\PascalCaseRule::class)]
#[CoversClass(\KaririCode\Transformer\Rule\String\MaskRule::class)]
#[CoversClass(\KaririCode\Transformer\Rule\String\ReverseRule::class)]
#[CoversClass(\KaririCode\Transformer\Rule\String\RepeatRule::class)]
final class StringRulesTest extends TestCase
{
    private function ctx(array $params = []): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test')->withParameters($params);
    }

    #[Test]
    public function testCamelCase(): void
    {
        $rule = new CamelCaseRule();
        $this->assertSame('helloWorld', $rule->transform('hello_world', $this->ctx()));
        $this->assertSame('helloWorld', $rule->transform('hello-world', $this->ctx()));
        $this->assertSame('helloWorld', $rule->transform('Hello World', $this->ctx()));
        $this->assertSame(42, $rule->transform(42, $this->ctx()));
    }

    #[Test]
    public function testSnakeCase(): void
    {
        $rule = new SnakeCaseRule();
        $this->assertSame('hello_world', $rule->transform('helloWorld', $this->ctx()));
        $this->assertSame('hello_world', $rule->transform('HelloWorld', $this->ctx()));
        $this->assertSame('hello_world', $rule->transform('Hello World', $this->ctx()));
    }

    #[Test]
    public function testKebabCase(): void
    {
        $rule = new KebabCaseRule();
        $this->assertSame('hello-world', $rule->transform('helloWorld', $this->ctx()));
        $this->assertSame('hello-world', $rule->transform('Hello World', $this->ctx()));
    }

    #[Test]
    public function testPascalCase(): void
    {
        $rule = new PascalCaseRule();
        $this->assertSame('HelloWorld', $rule->transform('hello_world', $this->ctx()));
        $this->assertSame('HelloWorld', $rule->transform('hello-world', $this->ctx()));
    }

    #[Test]
    public function testMask(): void
    {
        $rule = new MaskRule();
        $this->assertSame('529*****725', $rule->transform('52998224725', $this->ctx(['keep_start' => 3, 'keep_end' => 3])));
        $this->assertSame('ab', $rule->transform('ab', $this->ctx(['keep_start' => 3, 'keep_end' => 3]))); // too short
    }

    #[Test]
    public function testReverse(): void
    {
        $rule = new ReverseRule();
        $this->assertSame('olleH', $rule->transform('Hello', $this->ctx()));
        $this->assertSame('oluaP oãS', $rule->transform('São Paulo', $this->ctx()));
    }

    #[Test]
    public function testRepeat(): void
    {
        $rule = new RepeatRule();
        $this->assertSame('abab', $rule->transform('ab', $this->ctx(['times' => 2])));
        $this->assertSame('ab-ab-ab', $rule->transform('ab', $this->ctx(['times' => 3, 'separator' => '-'])));
    }

    #[Test]
    public function testGetName(): void
    {
        // String rules
        $this->assertSame('string.camel_case', (new CamelCaseRule())->getName());
        $this->assertSame('string.snake_case', (new SnakeCaseRule())->getName());
        $this->assertSame('string.kebab_case', (new KebabCaseRule())->getName());
        $this->assertSame('string.pascal_case', (new PascalCaseRule())->getName());
        $this->assertSame('string.mask', (new MaskRule())->getName());
        $this->assertSame('string.reverse', (new ReverseRule())->getName());
        $this->assertSame('string.repeat', (new RepeatRule())->getName());
    }
}
