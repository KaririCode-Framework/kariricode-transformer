# Framework KaririCode: transformer Component

## Desenvolvimento e Testes

Para fins de desenvolvimento e teste, este pacote usa Docker e Docker Compose para garantir consistência em diferentes ambientes. Um Makefile é fornecido para conveniência.

### Pré-requisitos

- Docker
- Docker Compose
- Make (opcional, mas recomendado para execução mais fácil de comandos)

### Configuração de Desenvolvimento

1. Clone o repositório:

   ```bash
   git clone https://github.com/KaririCode-Framework/kariricode-transformer.git
   cd kariricode-transformer
   ```

2. Configure o ambiente:

   ```bash
   make setup-env
   ```

3. Inicie os containers Docker:

   ```bash
   make up
   ```

4. Instale as dependências:

   ```bash
   make composer-install
   ```

### Comandos Make Disponíveis

- `make up`: Inicia todos os serviços em segundo plano
- `make down`: Para e remove todos os containers
- `make build`: Constrói imagens Docker
- `make shell`: Acessa o shell do container PHP
- `make test`: Executa testes
- `make coverage`: Executa cobertura de testes com formatação visual
- `make cs-fix`: Executa PHP CS Fixer para corrigir estilo de código
- `make quality`: Executa todos os comandos de qualidade (cs-check, test, security-check)

## Contribuindo

Nós recebemos contribuições para o componente KaririCode transformer! Aqui está como você pode contribuir:

1. Faça um fork do repositório
2. Crie um novo branch para sua feature ou correção de bug
3. Escreva testes para suas alterações
4. Implemente suas alterações
5. Execute a suite de testes e garanta que todos os testes passem
6. Envie um pull request com uma descrição clara de suas alterações

Por favor, leia nosso [Guia de Contribuição](CONTRIBUTING.md) para mais detalhes sobre nosso código de conduta e processo de desenvolvimento.

## Licença

Este projeto está licenciado sob a Licença MIT - veja o arquivo [LICENSE](LICENSE) para detalhes.

## Suporte e Comunidade

- **Documentação**: [https://kariricode.org/docs/transformer](https://kariricode.org/docs/transformer)
- **Issue Tracker**: [GitHub Issues](https://github.com/KaririCode-Framework/kariricode-transformer/issues)
- **Fórum da Comunidade**: [Comunidade KaririCode Club](https://kariricode.club)
- **Stack Overflow**: Marque suas perguntas com `kariricode-transformer`

---

Construído com ❤️ pela equipe KaririCode. Capacitando desenvolvedores para criar aplicações PHP mais seguras e robustas.
