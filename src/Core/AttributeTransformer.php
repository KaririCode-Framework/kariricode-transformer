<?php

declare(strict_types=1);

namespace KaririCode\Transformer\Core;

use KaririCode\Transformer\Attribute\Transform;
use KaririCode\Transformer\Result\TransformationResult;

final readonly class AttributeTransformer
{
    public function __construct(private TransformerEngine $engine) {}

    public function transform(object $object): TransformationResult
    {
        $ref = new \ReflectionClass($object);
        $data = [];
        $fieldRules = [];

        foreach ($ref->getProperties() as $property) {
            $attributes = $property->getAttributes(Transform::class);
            if ($attributes === []) {
                continue;
            }

            $field = $property->getName();
            try {
                $data[$field] = $property->getValue($object);
            } catch (\Error) {
                $data[$field] = null;
            }

            $rules = [];
            foreach ($attributes as $attribute) {
                /** @var Transform $transform */
                $transform = $attribute->newInstance();
                $rules = [...$rules, ...$transform->rules];
            }
            $fieldRules[$field] = $rules;
        }

        $result = $this->engine->transform($data, $fieldRules);

        foreach ($result->getTransformedData() as $field => $value) {
            if ($ref->hasProperty($field)) {
                $ref->getProperty($field)->setValue($object, $value);
            }
        }

        return $result;
    }
}
