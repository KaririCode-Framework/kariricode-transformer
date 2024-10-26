# KaririCode Framework: Transformer Component

Um componente poderoso e flexível para transformação de dados em PHP, parte do KaririCode Framework. Utiliza transformação baseada em atributos com processadores configuráveis para garantir consistência na transformação e formatação de dados em suas aplicações.

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
- Conjunto abrangente de transformadores integrados para casos comuns
- Fácil integração com outros componentes KaririCode
- Processadores configuráveis para lógica de transformação personalizada
- Arquitetura extensível permitindo transformadores personalizados
- Tratamento e relatório de erros robusto
- Pipeline de transformação encadeável para transformações complexas
- Suporte integrado para múltiplos cenários de transformação
- Transformação com segurança de tipos com recursos do PHP 8.3
- Preservação dos tipos de dados originais
- Opções flexíveis de formatação para diversos tipos de dados

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

class FormatadorDados
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

2. Configure o transformador e utilize-o:

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

$formatador = new FormatadorDados();
$resultado = $transformer->transform($formatador);

if ($resultado->isValid()) {
    echo "Data: " . $formatador->getData() . "\n";          // Saída: 2024-12-25
    echo "Preço: " . $formatador->getPreco() . "\n";        // Saída: 1.234,56
    echo "Telefone: " . $formatador->getTelefone() . "\n";  // Saída: (11) 99988-7766
}
```

### Uso Avançado: Formatação de Dados

Aqui está um exemplo de como usar o KaririCode Transformer em um cenário real, demonstrando várias capacidades de transformação:

```php
use KaririCode\Transformer\Attribute\Transform;

class TransformadorDadosComplexos
{
    #[Transform(
        processors: ['case' => ['case' => 'snake']]
    )]
    private string $texto = 'transformarEsseTextoParaSnakeCase';

    #[Transform(
        processors: ['slug' => []]
    )]
    private string $titulo = 'Este é um Título para URL!';

    #[Transform(
        processors: ['arrayKey' => ['case' => 'camel']]
    )]
    private array $dados = [
        'nome_usuario' => 'João Silva',
        'endereco_email' => 'joao@exemplo.com',
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

## Exemplos Práticos

### 1. Exemplos de Transformação de Strings

```php
class ExemploTransformadorString
{
    #[Transform(
        processors: ['case' => ['case' => 'snake']]
    )]
    private string $nomeMetodo = 'obterDadosPerfilUsuario';

    #[Transform(
        processors: ['case' => ['case' => 'camel']]
    )]
    private string $nomeVariavel = 'dados_perfil_usuario';

    #[Transform(
        processors: ['slug' => ['separator' => '-']]
    )]
    private string $tituloArtigo = 'Como Usar os Recursos do PHP 8.3!';

    #[Transform(
        processors: ['mask' => ['type' => 'phone']]
    )]
    private string $numeroTelefone = '11999887766';
}

// Saída:
// nomeMetodo: obter_dados_perfil_usuario
// nomeVariavel: dadosPerfilUsuario
// tituloArtigo: como-usar-os-recursos-do-php-8-3
// numeroTelefone: (11) 99988-7766
```

### 2. Formatação de Números e Moeda

```php
class ExemploTransformadorMoeda
{
    #[Transform(
        processors: ['number' => [
            'decimals' => 2,
            'decimalPoint' => ',',
            'thousandsSeparator' => '.'
        ]]
    )]
    private float $preco = 1234567.89;

    #[Transform(
        processors: ['number' => [
            'decimals' => 0,
            'thousandsSeparator' => '.'
        ]]
    )]
    private int $quantidade = 1000000;
}

// Saída:
// preco: 1.234.567,89
// quantidade: 1.000.000
```

### 3. Transformação de Data para Diferentes Localizações

```php
class ExemploTransformadorData
{
    #[Transform(
        processors: ['date' => [
            'inputFormat' => 'd/m/Y',
            'outputFormat' => 'Y-m-d'
        ]]
    )]
    private string $dataSQL = '25/12/2024';

    #[Transform(
        processors: ['date' => [
            'inputFormat' => 'Y-m-d',
            'outputFormat' => 'd \d\e F \d\e Y'
        ]]
    )]
    private string $dataExibicao = '2024-12-25';

    #[Transform(
        processors: ['date' => [
            'inputFormat' => 'Y-m-d H:i:s',
            'outputFormat' => 'd/m/Y H:i',
            'inputTimezone' => 'UTC',
            'outputTimezone' => 'America/Sao_Paulo'
        ]]
    )]
    private string $timestamp = '2024-12-25 15:30:00';
}

// Saída:
// dataSQL: 2024-12-25
// dataExibicao: 25 de Dezembro de 2024
// timestamp: 25/12/2024 12:30
```

### 4. Transformação de Array para Resposta de API

```php
class ExemploTransformadorRespostaAPI
{
    #[Transform(
        processors: ['arrayKey' => ['case' => 'camel']]
    )]
    private array $dadosUsuario = [
        'id_usuario' => 123,
        'nome_completo' => 'João Silva',
        'sobrenome' => 'Santos',
        'endereco_email' => 'joao@exemplo.com',
        'telefones' => [
            'telefone_residencial' => '1234567890',
            'telefone_comercial' => '0987654321'
        ]
    ];

    #[Transform(
        processors: ['arrayFlat' => ['separator' => '.']]
    )]
    private array $configAninhada = [
        'banco_dados' => [
            'mysql' => [
                'host' => 'localhost',
                'porta' => 3306
            ]
        ],
        'cache' => [
            'redis' => [
                'host' => '127.0.0.1',
                'porta' => 6379
            ]
        ]
    ];
}

// Saída:
// dadosUsuario:
// {
//     "idUsuario": 123,
//     "nomeCompleto": "João Silva",
//     "sobrenome": "Santos",
//     "enderecoEmail": "joao@exemplo.com",
//     "telefones": {
//         "telefoneResidencial": "1234567890",
//         "telefoneComercial": "0987654321"
//     }
// }
//
// configAninhada:
// {
//     "banco_dados.mysql.host": "localhost",
//     "banco_dados.mysql.porta": 3306,
//     "cache.redis.host": "127.0.0.1",
//     "cache.redis.porta": 6379
// }
```

### 5. Transformação de Template para Notificações

```php
class ExemploTransformadorNotificacao
{
    #[Transform(
        processors: [
            'template' => [
                'template' => <<<TEMPLATE
                Prezado(a) {{nomeUsuario}},

                Seu pedido #{{numeroPedido}} foi {{status}}.
                {{#if rastreio}}
                Rastreie sua encomenda: {{rastreio}}
                {{/if}}

                Total: {{moeda}}{{valor}}

                Atenciosamente,
                {{nomeEmpresa}}
                TEMPLATE,
                'preserveData' => true
            ]
        ]
    )]
    private array $dadosEmail = [
        'nomeUsuario' => 'João Silva',
        'numeroPedido' => 'PED-12345',
        'status' => 'enviado',
        'rastreio' => 'RASTR-XYZ-789',
        'moeda' => 'R$',
        'valor' => '299,99',
        'nomeEmpresa' => 'Loja KaririCode'
    ];
}

// Saída:
// Dados Originais:
// {
//     "nomeUsuario": "João Silva",
//     "numeroPedido": "PED-12345",
//     "status": "enviado",
//     "rastreio": "RASTR-XYZ-789",
//     "moeda": "R$",
//     "valor": "299,99",
//     "nomeEmpresa": "Loja KaririCode"
// }
//
// Template Renderizado:
// Prezado(a) João Silva,
//
// Seu pedido #PED-12345 foi enviado.
// Rastreie sua encomenda: RASTR-XYZ-789
//
// Total: R$299,99
//
// Atenciosamente,
// Loja KaririCode
```

### 6. Exemplo de Transformação em Cadeia

```php
class ExemploTransformadorCadeia
{
    #[Transform(
        processors: [
            'case' => ['case' => 'lower'],
            'slug' => ['separator' => '-'],
            'template' => [
                'template' => '{{data}}-{{slug}}',
                'preserveData' => true
            ]
        ]
    )]
    private array $dadosUrl = [
        'data' => '2024-01-15',
        'slug' => 'Como Encadear Múltiplos Transformadores'
    ];
}

// Saída:
// 2024-01-15-como-encadear-multiplos-transformadores
```

## Transformadores Disponíveis

### Transformadores de String

- **CaseTransformer**: Transforma o caso de strings (camel, snake, pascal, kebab).

  - **Opções de Configuração**:
    - `case`: Formato alvo (lower, upper, title, sentence, camel, pascal, snake, kebab)
    - `preserveNumbers`: Se deve preservar números na transformação

- **MaskTransformer**: Aplica máscaras em strings (telefone, CPF, CNPJ, etc.).

  - **Opções de Configuração**:
    - `mask`: Padrão personalizado de máscara
    - `type`: Tipo predefinido de máscara
    - `placeholder`: Caractere de placeholder da máscara

- **SlugTransformer**: Gera slugs amigáveis para URLs.

  - **Opções de Configuração**:
    - `separator`: Caractere separador
    - `lowercase`: Converter para minúsculas
    - `replacements`: Substituições personalizadas de caracteres

- **TemplateTransformer**: Processa templates com substituição de variáveis.
  - **Opções de Configuração**:
    - `template`: String do template
    - `removeUnmatchedTags`: Remove placeholders não correspondidos
    - `preserveData`: Mantém os dados originais no resultado

### Transformadores de Dados

- **DateTransformer**: Converte entre formatos de data.

  - **Opções de Configuração**:
    - `inputFormat`: Formato de entrada da data
    - `outputFormat`: Formato de saída da data
    - `inputTimezone`: Fuso horário de entrada
    - `outputTimezone`: Fuso horário de saída

- **NumberTransformer**: Formata números com configurações locais.

  - **Opções de Configuração**:
    - `decimals`: Número de casas decimais
    - `decimalPoint`: Separador decimal
    - `thousandsSeparator`: Separador de milhares
    - `roundUp`: Arredonda para cima

- **JsonTransformer**: Manipula codificação/decodificação JSON.
  - **Opções de Configuração**:
    - `encodeOptions`: Opções de codificação JSON
    - `preserveType`: Mantém o tipo de dado original
    - `assoc`: Usa arrays associativos

### Transformadores de Array

- **ArrayFlattenTransformer**: Achata arrays aninhados.

  - **Opções de Configuração**:
    - `depth`: Profundidade máxima para achatar
    - `separator`: Separador de chaves para estrutura achatada

- **ArrayGroupTransformer**: Agrupa elementos do array por chave.

  - **Opções de Configuração**:
    - `groupBy`: Chave para agrupamento
    - `preserveKeys`: Mantém as chaves originais

- **ArrayKeyTransformer**: Transforma chaves do array.

  - **Opções de Configuração**:
    - `case`: Caso alvo para as chaves
    - `recursive`: Aplica em arrays aninhados

- **ArrayMapTransformer**: Mapeia chaves do array para nova estrutura.
  - **Opções de Configuração**:
    - `mapping`: Configuração de mapeamento de chaves
    - `removeUnmapped`: Remove chaves não mapeadas
    - `recursive`: Aplica em arrays aninhados

### Transformadores Compostos

- **ChainTransformer**: Executa múltiplos transformadores em sequência.

  - **Opções de Configuração**:
    - `transformers`: Array de transformadores a executar
    - `stopOnError`: Para a cadeia no primeiro erro

- **ConditionalTransformer**: Aplica transformações baseadas em condições.
  - **Opções de Configuração**:
    - `condition`: Callback de condição
    - `transformer`: Transformador a aplicar
    - `defaultValue`: Valor quando a condição falha

## Configuração

Os transformadores podem ser configurados globalmente ou por instância. Exemplo de configuração do NumberTransformer:

```php
use KaririCode\Transformer\Processor\Data\NumberTransformer;

$transformadorNumero = new NumberTransformer();
$transformadorNumero->configure([
    'decimals' => 2,
    'decimalPoint' => ',',
    'thousandsSeparator' => '.',
]);

$registry->register('transformer', 'number', $transformadorNumero);
```

## Integração com Outros Componentes KaririCode

O componente Transformer se integra com:

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

- `make up`: Inicia os serviços
- `make down`: Para os serviços
- `make test`: Executa os testes
- `make coverage`: Gera relatório de cobertura
- `make cs-fix`: Corrige estilo do código
- `make quality`: Executa verificações de qualidade

## Contribuindo

Contribuições são bem-vindas! Por favor, veja nosso [Guia de Contribuição](CONTRIBUTING.md).

## Licença

Licença MIT - veja o arquivo [LICENSE](LICENSE).

## Suporte e Comunidade

- **Documentação**: [https://kariricode.org/docs/transformer](https://kariricode.org/docs/transformer)
- **Issues**: [GitHub Issues](https://github.com/KaririCode-Framework/kariricode-transformer/issues)
- **Fórum**: [Comunidade KaririCode Club](https://kariricode.club)
- **Stack Overflow**: Marque com `kariricode-transformer`

---

Construído com ❤️ pelo time KaririCode. Capacitando desenvolvedores a criar aplicações PHP mais seguras e robustas.
