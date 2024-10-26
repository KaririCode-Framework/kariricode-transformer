# KaririCode Framework: Transformer Component

[![en](https://img.shields.io/badge/lang-en-red.svg)](README.md) [![pt-br](https://img.shields.io/badge/lang-pt--br-green.svg)](README.pt-br.md)

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white) ![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white) ![PHPUnit](https://img.shields.io/badge/PHPUnit-3776AB?style=for-the-badge&logo=php&logoColor=white)

Um componente poderoso e flexível de transformação de dados para PHP, parte do Framework KaririCode. Ele usa transformação baseada em atributos com processadores configuráveis para garantir transformação e formatação consistente de dados em suas aplicações.

## Índice

- [Funcionalidades](#funcionalidades)
- [Instalação](#instalação)
- [Uso](#uso)
  - [Uso Básico](#uso-básico)
  - [Uso Avançado: Formatação de Dados](#uso-avançado-formatação-de-dados)
- [Transformadores Disponíveis](#transformadores-disponíveis)
  - [Transformadores de String](#transformadores-de-string)
  - [Transformadores de Dados](#transformadores-de-dados)
  - [Transformadores de Array](#transformadores-de-array)
  - [Transformadores Compostos](#transformadores-compostos)
- [Configuração](#configuração)
- [Integração com Outros Componentes KaririCode](#integração-com-outros-componentes-kariricode)
- [Desenvolvimento e Testes](#desenvolvimento-e-testes)
- [Contribuindo](#contribuindo)
- [Licença](#licença)
- [Suporte e Comunidade](#suporte-e-comunidade)

## Funcionalidades

- Transformação baseada em atributos para propriedades de objetos
- Conjunto abrangente de transformadores integrados para casos de uso comuns
- Fácil integração com outros componentes KaririCode
- Processadores configuráveis para lógica de transformação personalizada
- Arquitetura extensível permitindo transformadores personalizados
- Tratamento e relatório de erros robusto
- Pipeline de transformação encadeável para transformação complexa de dados
- Suporte integrado para múltiplos cenários de transformação
- Transformação type-safe com recursos do PHP 8.3
- Preservação dos tipos de dados originais
- Opções flexíveis de formatação para vários tipos de dados

## Instalação

Você pode instalar o componente Transformer via Composer:

```bash
composer require kariricode/transformer
```

### Requisitos

- PHP 8.3 ou superior
- Composer
- Extensões: `ext-mbstring`, `ext-json`

## Uso

### Uso Básico

1. Defina sua classe de dados com atributos de transformação:

```php
use KaririCode\Transformer\Attribute\Transform;

class FormatadorDeDados
{
    #[Transform(
        processors: ['date' => ['inputFormat' => 'd/m/Y', 'outputFormat' => 'Y-m-d']]
    )]
    private string $data = '25/12/2024';

    #[Transform(
        processors: ['number' => ['decimals' => 2, 'decimalPoint' => ',', 'thousandsSeparator' => '.']]
    )]
    private float $preco = 1234.56;

    #[Transform(
        processors: ['mask' => ['type' => 'phone']]
    )]
    private string $telefone = '11999887766';

    // Getters e setters...
}
```

2. Configure o transformador e use-o:

```php
use KaririCode\ProcessorPipeline\ProcessorRegistry;
use KaririCode\Transformer\Transformer;
use KaririCode\Transformer\Processor\Data\{DateTransformer, NumberTransformer};
use KaririCode\Transformer\Processor\String\MaskTransformer;

$registry = new ProcessorRegistry();
$registry->register('transformer', 'date', new DateTransformer());
$registry->register('transformer', 'number', new NumberTransformer());
$registry->register('transformer', 'mask', new MaskTransformer());

$transformer = new Transformer($registry);

$formatador = new FormatadorDeDados();
$resultado = $transformer->transform($formatador);

if ($resultado->isValid()) {
    echo "Data: " . $formatador->getData() . "\n";        // Saída: 2024-12-25
    echo "Preço: " . $formatador->getPreco() . "\n";      // Saída: 1.234,56
    echo "Telefone: " . $formatador->getTelefone() . "\n"; // Saída: (11) 99988-7766
}
```

### Uso Avançado: Formatação de Dados

Aqui está um exemplo de como usar o Transformer KaririCode em um cenário do mundo real, demonstrando várias capacidades de transformação:

```php
use KaririCode\Transformer\Attribute\Transform;

class TransformadorDeDadosComplexos
{
    #[Transform(
        processors: ['case' => ['case' => 'snake']]
    )]
    private string $texto = 'transformarEsteTextoParaSnakeCase';

    #[Transform(
        processors: ['slug' => []]
    )]
    private string $titulo = 'Este é um Título para URL!';

    #[Transform(
        processors: ['arrayKey' => ['case' => 'camel']]
    )]
    private array $dados = [
        'nome_usuario' => 'João Silva',
        'endereco_email' => 'joao@exemplo.com.br',
        'numero_telefone' => '1234567890'
    ];

    #[Transform(
        processors: [
            'template' => [
                'template' => 'Olá {{nome}}, seu pedido #{{numero_pedido}} está {{status}}',
                'removeUnmatchedTags' => true,
                'preserveData' => true
            ]
        ]
    )]
    private array $dadosTemplate = [
        'nome' => 'João',
        'numero_pedido' => '12345',
        'status' => 'concluído'
    ];

    // Getters e setters...
}
```

## Transformadores Disponíveis

### Transformadores de String

- **CaseTransformer**: Transforma o caso da string (camel, snake, pascal, kebab).

  - **Opções de Configuração**:
    - `case`: Formato do caso alvo (lower, upper, title, sentence, camel, pascal, snake, kebab)
    - `preserveNumbers`: Se deve preservar números na transformação

- **MaskTransformer**: Aplica máscaras a strings (telefone, CPF, CNPJ, etc.).

  - **Opções de Configuração**:
    - `mask`: Padrão de máscara personalizado
    - `type`: Tipo de máscara predefinido
    - `placeholder`: Caractere de placeholder da máscara

- **SlugTransformer**: Gera slugs amigáveis para URL.

  - **Opções de Configuração**:
    - `separator`: Caractere separador
    - `lowercase`: Converter para minúsculas
    - `replacements`: Substituições de caracteres personalizadas

- **TemplateTransformer**: Processa templates com substituição de variáveis.
  - **Opções de Configuração**:
    - `template`: String do template
    - `removeUnmatchedTags`: Remove placeholders não correspondidos
    - `preserveData`: Mantém dados originais no resultado

### Transformadores de Dados

- **DateTransformer**: Converte entre formatos de data.

  - **Opções de Configuração**:
    - `inputFormat`: Formato de data de entrada
    - `outputFormat`: Formato de data de saída
    - `inputTimezone`: Fuso horário de entrada
    - `outputTimezone`: Fuso horário de saída

- **NumberTransformer**: Formata números com configurações específicas de localidade.

  - **Opções de Configuração**:
    - `decimals`: Número de casas decimais
    - `decimalPoint`: Separador decimal
    - `thousandsSeparator`: Separador de milhares
    - `roundUp`: Arredondar decimais para cima

- **JsonTransformer**: Lida com codificação/decodificação JSON.
  - **Opções de Configuração**:
    - `encodeOptions`: Opções de codificação JSON
    - `preserveType`: Mantém tipo de dado original
    - `assoc`: Usa arrays associativos

### Transformadores de Array

- **ArrayFlattenTransformer**: Achata arrays aninhados.

  - **Opções de Configuração**:
    - `depth`: Profundidade máxima para achatar
    - `separator`: Separador de chaves para estrutura achatada

- **ArrayGroupTransformer**: Agrupa elementos do array por chave.

  - **Opções de Configuração**:
    - `groupBy`: Chave para agrupar
    - `preserveKeys`: Mantém chaves originais

- **ArrayKeyTransformer**: Transforma chaves do array.

  - **Opções de Configuração**:
    - `case`: Caso alvo para chaves
    - `recursive`: Aplicar a arrays aninhados

- **ArrayMapTransformer**: Mapeia chaves do array para nova estrutura.
  - **Opções de Configuração**:
    - `mapping`: Configuração de mapeamento de chaves
    - `removeUnmapped`: Remove chaves não mapeadas
    - `recursive`: Aplicar a arrays aninhados

### Transformadores Compostos

- **ChainTransformer**: Executa múltiplos transformadores em sequência.

  - **Opções de Configuração**:
    - `transformers`: Array de transformadores para executar
    - `stopOnError`: Para cadeia no primeiro erro

- **ConditionalTransformer**: Aplica transformações baseadas em condições.
  - **Opções de Configuração**:
    - `condition`: Callback de condição
    - `transformer`: Transformador a aplicar
    - `defaultValue`: Valor quando condição falha

## Configuração

Transformadores podem ser configurados globalmente ou por instância. Exemplo de configuração do NumberTransformer:

```php
use KaririCode\Transformer\Processor\Data\NumberTransformer;

$numberTransformer = new NumberTransformer();
$numberTransformer->configure([
    'decimals' => 2,
    'decimalPoint' => ',',
    'thousandsSeparator' => '.',
]);

$registry->register('transformer', 'number', $numberTransformer);
```

## Integração com Outros Componentes KaririCode

O componente Transformer integra-se com:

- **KaririCode\Contract**: Fornece interfaces para integração de componentes
- **KaririCode\ProcessorPipeline**: Usado para pipelines de transformação
- **KaririCode\PropertyInspector**: Processa atributos de transformação

## Exemplo de Registro

Exemplo completo de configuração do registro:

```php
$registry = new ProcessorRegistry();

// Registrar Transformadores de String
$registry->register('transformer', 'case', new CaseTransformer())
         ->register('transformer', 'mask', new MaskTransformer())
         ->register('transformer', 'slug', new SlugTransformer())
         ->register('transformer', 'template', new TemplateTransformer());

// Registrar Transformadores de Dados
$registry->register('transformer', 'date', new DateTransformer())
         ->register('transformer', 'number', new NumberTransformer())
         ->register('transformer', 'json', new JsonTransformer());

// Registrar Transformadores de Array
$registry->register('transformer', 'arrayFlat', new ArrayFlattenTransformer())
         ->register('transformer', 'arrayGroup', new ArrayGroupTransformer())
         ->register('transformer', 'arrayKey', new ArrayKeyTransformer())
         ->register('transformer', 'arrayMap', new ArrayMapTransformer());
```

## Desenvolvimento e Testes

Configuração de desenvolvimento similar ao componente Validator, usando Docker e comandos Make.

### Comandos Make Disponíveis

- `make up`: Iniciar serviços
- `make down`: Parar serviços
- `make test`: Executar testes
- `make coverage`: Gerar relatório de cobertura
- `make cs-fix`: Corrigir estilo de código
- `make quality`: Executar verificações de qualidade

## Contribuindo

Contribuições são bem-vindas! Por favor, veja nosso [Guia de Contribuição](CONTRIBUTING.md).

## Licença

Licença MIT - veja arquivo [LICENSE](LICENSE).

## Suporte e Comunidade

- **Documentação**: [https://kariricode.org/docs/transformer](https://kariricode.org/docs/transformer)
- **Issues**: [GitHub Issues](https://github.com/KaririCode-Framework/kariricode-transformer/issues)
- **Fórum**: [Comunidade KaririCode Club](https://kariricode.club)
- **Stack Overflow**: Marque com `kariricode-transformer`

---

Feito com ❤️ pela equipe KaririCode. Transformando dados com elegância e precisão.
