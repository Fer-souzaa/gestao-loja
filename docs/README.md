# Documentação do Projeto

Bem-vindo à documentação do sistema de gestão de loja. Este diretório contém informações técnicas e funcionais sobre o projeto e seus módulos.

## Navegação

- [Índice](index.md) - Navegação completa
- [Arquitetura](arquitetura.md) - Visão geral da arquitetura
- [Autenticação](autenticacao.md) - Sistema de autenticação
- [Banco de Dados](database.md) - Convenções e estrutura

## Módulos

- [Visão Geral dos Módulos](modules/README.md)
- [Clientes](modules/customer.md)
- [Funcionários](modules/employee.md)
- [Empresas](modules/company.md)
- [Pessoas](modules/person.md)
- [Autenticação](modules/auth.md)
- [Cores](modules/color.md)
- [Coleções](modules/collection.md)

## API

- [Visão Geral da API](api/README.md)

---

## Visão Geral do Sistema

O sistema é uma API REST para gestão de loja, desenvolvido em Laravel 12 com PHP 8.2+.

### Principais Funcionalidades

- Gestão de clientes e vendedores
- Cadastro de empresas (multi-tenant)
- Autenticação via tokens (Laravel Passport)
- Cadastro de cores e coleções de produtos

### Tecnologias

| Tecnologia | Descrição |
|------------|-----------|
| Laravel 12 | Framework PHP |
| PostgreSQL | Banco de dados |
| Redis | Cache |
| Laravel Passport | Autenticação |
| Pest PHP | Testes |
| Laravel Pint | Code style (PSR-12) |
