# KaririCode Framework: transformer Component

## Development and Testing

For development and testing purposes, this package uses Docker and Docker Compose to ensure consistency across different environments. A Makefile is provided for convenience.

### Prerequisites

- Docker
- Docker Compose
- Make (optional, but recommended for easier command execution)

### Development Setup

1. Clone the repository:

   ```bash
   git clone https://github.com/KaririCode-Framework/kariricode-transformer.git
   cd kariricode-transformer
   ```

2. Set up the environment:

   ```bash
   make setup-env
   ```

3. Start the Docker containers:

   ```bash
   make up
   ```

4. Install dependencies:

   ```bash
   make composer-install
   ```

### Available Make Commands

- `make up`: Start all services in the background
- `make down`: Stop and remove all containers
- `make build`: Build Docker images
- `make shell`: Access the PHP container shell
- `make test`: Run tests
- `make coverage`: Run test coverage with visual formatting
- `make cs-fix`: Run PHP CS Fixer to fix code style
- `make quality`: Run all quality commands (cs-check, test, security-check)

## Contributing

We welcome contributions to the KaririCode transformer component! Here's how you can contribute:

1. Fork the repository
2. Create a new branch for your feature or bug fix
3. Write tests for your changes
4. Implement your changes
5. Run the test suite and ensure all tests pass
6. Submit a pull request with a clear description of your changes

Please read our [Contributing Guide](CONTRIBUTING.md) for more details on our code of conduct and development process.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support and Community

- **Documentation**: [https://kariricode.org/docs/transformer](https://kariricode.org/docs/transformer)
- **Issue Tracker**: [GitHub Issues](https://github.com/KaririCode-Framework/kariricode-transformer/issues)
- **Community Forum**: [KaririCode Club Community](https://kariricode.club)
- **Stack Overflow**: Tag your questions with `kariricode-transformer`

---

Built with ❤️ by the KaririCode team. Empowering developers to create more secure and robust PHP applications.
