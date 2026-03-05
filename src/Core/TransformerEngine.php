<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Core;

use KaririCode\Transformer\Configuration\TransformerConfiguration;
use KaririCode\Transformer\Contract\RuleRegistry;
use KaririCode\Transformer\Contract\TransformationRule;
use KaririCode\Transformer\Result\FieldTransformation;
use KaririCode\Transformer\Result\TransformationResult;

/**
 * Central transformation orchestrator.
 *
 * Applies per-field rule pipelines to input data, returning a
 * TransformationResult with transformed data and transformation log.
 *
 * @package KaririCode\Transformer\Core
 * @author  Walmir Silva <walmir.silva@kariricode.org>
 * @since   3.1.0 ARFA 1.3
 */
final class TransformerEngine
{
    public function __construct(
        private readonly RuleRegistry $registry,
        private readonly ?TransformerConfiguration $configuration = null,
    ) {}

    /**
     * @param array<string, mixed> $data
     * @param array<string, list<string|TransformationRule|array{0: string|TransformationRule, 1: array<string, mixed>}>> $fieldRules
     */
    public function transform(array $data, array $fieldRules): TransformationResult
    {
        $config = $this->configuration ?? new TransformerConfiguration();
        $result = new TransformationResult($data, $data);
        $baseContext = TransformationContextImpl::create($data);

        foreach ($fieldRules as $field => $rules) {
            $value = $this->resolveValue($data, $field);
            $fieldContext = $baseContext->withField($field);

            foreach ($rules as $ruleDefinition) {
                [$rule, $params] = $this->resolveRule($ruleDefinition);
                $transformationContext = $params !== [] ? $fieldContext->withParameters($params) : $fieldContext;

                $before = $value;
                $value = $rule->transform($value, $transformationContext);

                if ($config->trackTransformations && $before !== $value) {
                    $result->addTransformation(new FieldTransformation($field, $rule->getName(), $before, $value));
                }
            }

            $result->setTransformedValue($field, $value);
        }

        return $result;
    }

    private function resolveValue(array $data, string $field): mixed
    {
        if (array_key_exists($field, $data)) {
            return $data[$field];
        }
        $segments = explode('.', $field);
        $current = $data;
        foreach ($segments as $segment) {
            if (!is_array($current) || !array_key_exists($segment, $current)) {
                return null;
            }
            $current = $current[$segment];
        }
        return $current;
    }

    /** @return array{0: TransformationRule, 1: array<string, mixed>} */
    private function resolveRule(string|array|TransformationRule $definition): array
    {
        if ($definition instanceof TransformationRule) {
            return [$definition, []];
        }
        if (is_string($definition)) {
            return [$this->registry->resolve($definition), []];
        }
        $ruleRef = $definition[0];
        $params = $definition[1] ?? [];
        $rule = $ruleRef instanceof TransformationRule ? $ruleRef : $this->registry->resolve($ruleRef);
        return [$rule, $params];
    }
}
