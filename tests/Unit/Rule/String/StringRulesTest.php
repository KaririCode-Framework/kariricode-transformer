<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Tests\Unit\Rule\String;

use KaririCode\Transformer\Core\TransformationContextImpl;
use KaririCode\Transformer\Contract\TransformationContext;
use KaririCode\Transformer\Rule\String\{CamelCaseRule, SnakeCaseRule, KebabCaseRule, PascalCaseRule, MaskRule, ReverseRule, RepeatRule};
use PHPUnit\Framework\TestCase;

final class StringRulesTest extends TestCase
{
    private function ctx(array $params = []): TransformationContext
    {
        return TransformationContextImpl::create([])->withField('test')->withParameters($params);
    }

    public function testCamelCase(): void
    {
        $rule = new CamelCaseRule();
        $this->assertSame('helloWorld', $rule->transform('hello_world', $this->ctx()));
        $this->assertSame('helloWorld', $rule->transform('hello-world', $this->ctx()));
        $this->assertSame('helloWorld', $rule->transform('Hello World', $this->ctx()));
        $this->assertSame(42, $rule->transform(42, $this->ctx()));
    }

    public function testSnakeCase(): void
    {
        $rule = new SnakeCaseRule();
        $this->assertSame('hello_world', $rule->transform('helloWorld', $this->ctx()));
        $this->assertSame('hello_world', $rule->transform('HelloWorld', $this->ctx()));
        $this->assertSame('hello_world', $rule->transform('Hello World', $this->ctx()));
    }

    public function testKebabCase(): void
    {
        $rule = new KebabCaseRule();
        $this->assertSame('hello-world', $rule->transform('helloWorld', $this->ctx()));
        $this->assertSame('hello-world', $rule->transform('Hello World', $this->ctx()));
    }

    public function testPascalCase(): void
    {
        $rule = new PascalCaseRule();
        $this->assertSame('HelloWorld', $rule->transform('hello_world', $this->ctx()));
        $this->assertSame('HelloWorld', $rule->transform('hello-world', $this->ctx()));
    }

    public function testMask(): void
    {
        $rule = new MaskRule();
        $this->assertSame('529*****725', $rule->transform('52998224725', $this->ctx(['keep_start' => 3, 'keep_end' => 3])));
        $this->assertSame('ab', $rule->transform('ab', $this->ctx(['keep_start' => 3, 'keep_end' => 3]))); // too short
    }

    public function testReverse(): void
    {
        $rule = new ReverseRule();
        $this->assertSame('olleH', $rule->transform('Hello', $this->ctx()));
        $this->assertSame('oluaP oãS', $rule->transform('São Paulo', $this->ctx()));
    }

    public function testRepeat(): void
    {
        $rule = new RepeatRule();
        $this->assertSame('abab', $rule->transform('ab', $this->ctx(['times' => 2])));
        $this->assertSame('ab-ab-ab', $rule->transform('ab', $this->ctx(['times' => 3, 'separator' => '-'])));
    }

    public function testGetName(): void
    {
        // String rules
        $this->assertIsString((new \KaririCode\Transformer\Rule\String\CamelCaseRule())->getName());
        $this->assertIsString((new \KaririCode\Transformer\Rule\String\SnakeCaseRule())->getName());
        $this->assertIsString((new \KaririCode\Transformer\Rule\String\KebabCaseRule())->getName());
        $this->assertIsString((new \KaririCode\Transformer\Rule\String\PascalCaseRule())->getName());
        $this->assertIsString((new \KaririCode\Transformer\Rule\String\MaskRule())->getName());
        $this->assertIsString((new \KaririCode\Transformer\Rule\String\ReverseRule())->getName());
        $this->assertIsString((new \KaririCode\Transformer\Rule\String\RepeatRule())->getName());
    }
}
