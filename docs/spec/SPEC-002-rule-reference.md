# SPEC-002: Rule Reference

**Version:** 3.1.0 | **ARFA:** 1.3 V4.0

## Brazilian Rules
| Alias | Description |
|-------|-------------|
| `cpf.mask` | Format raw digits → 000.000.000-00 |
| `cnpj.mask` | Format raw digits → 00.000.000/0000-00 |
| `cep.mask` | Format raw digits → 00000-000 |
| `phone.mask` | Format → (00) 00000-0000 |

## Date Rules
| Alias | Parameters | Description |
|-------|-----------|-------------|
| `date.format` | `from`, `to` | Reformat date string between two formats |
| `date.to_timestamp` | — | Date string → Unix timestamp |
| `date.to_iso` | — | Any date → ISO 8601 |
| `date.age` | — | Birthdate string → age integer |

## Numeric Rules
| Alias | Parameters | Description |
|-------|-----------|-------------|
| `number.round` | `precision: int` | Round to decimal places |
| `number.abs` | — | Absolute value |
| `number.clamp` | `min`, `max` | Clamp to range |

## String Rules
| Alias | Description |
|-------|-------------|
| `trim` | Remove whitespace |
| `lowercase` / `uppercase` | Case conversion |
| `capitalize` | Capitalise first letter of each word |
| `slug` | Convert to URL-safe slug |
| `camel_case` / `snake_case` / `pascal_case` / `kebab_case` | Case style |
| `truncate` | Truncate with suffix |
| `remove_whitespace` | Remove all whitespace |
| `remove_special_chars` | Strip non-alphanumeric |

## Structure Rules
| Alias | Description |
|-------|-------------|
| `encode.base64` / `decode.base64` | Base64 ↔ string |
| `encode.url` / `decode.url` | URL encode/decode |
| `encode.html` / `decode.html` | HTML entities |
| `json.encode` / `json.decode` | JSON ↔ array |
| `hash.md5` / `hash.sha256` / `hash.bcrypt` | Hashing |
