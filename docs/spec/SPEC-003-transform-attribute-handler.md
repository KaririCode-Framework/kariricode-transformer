# SPEC-003: #[Transform] Attribute Handler

**Version:** 3.2.0 | **ARFA:** 1.3 V4.0

## 1. Attribute Definition

```php
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
final readonly class Transform
{
    public function __construct(
        public readonly array|string ...$rules,
    ) {}
}
```

## 2. Usage

```php
final class UserDTO
{
    #[Transform('trim', 'lowercase')]
    public string $email;

    #[Transform(['truncate', ['length' => 50]])]
    public string $bio;

    #[Transform('cpf.mask')]
    public string $cpf;
}
```

## 3. Handler Lifecycle

```
AttributeTransformer::transform($dto)
  │
  ├── PropertyInspector::inspect($dto, TransformAttributeHandler)
  │       └── Build fieldRules map from #[Transform] metadata
  │
  └── TransformerEngine::transform($data, $fieldRules)
```
