# Diretrizes Unificadas do Projeto (Laravel Boost + Junie)

## 1. Contexto Fundamental e Stack Tecnológica
Este projeto utiliza um ecossistema moderno e estrito. O agente deve respeitar rigorosamente as versões e tecnologias abaixo para evitar a geração de código depreciado:
- **PHP:** 8.4
- **Framework:** Laravel v12
- **Banco de Dados:** PostgreSQL 17
- **Testes:** Pest v4 / PHPUnit v12
- **Infraestrutura:** Docker, Docker Compose, Nginx (Proxy Reverso) em ambiente Linux/WSL.
- **Ecossistema:** Horizon v5, Prompts v0, Pint v1, Sail v1 e MCP v0.

## 2. Ferramentas do Agente (Laravel Boost MCP)
Você, como agente, possui ferramentas específicas projetadas para este ambiente. Utilize-as proativamente:
- **`search-docs` (Criticamente Importante):** Use esta ferramenta antes de sugerir qualquer alteração ou escrever código. Passe um array de pacotes para filtrar a documentação específica da versão (ex: `['routing rate limiting']`). Nunca adicione o nome do pacote na query.
- **`list-artisan-commands`:** Verifique os comandos e parâmetros disponíveis antes de executá-los. Sempre passe `--no-interaction` nos comandos.
- **`tinker` & `database-query`:** Use para debugar código PHP ou consultar o banco diretamente.
- **`browser-logs`:** Leia os logs recentes do navegador para debugar exceções de frontend.

## 3. Padrões de Código PHP e Qualidade
- **Sintaxe:** Sempre use chaves `{}` para estruturas de controle, mesmo as de uma linha.
- **Construtores:** Use a promoção de propriedades do PHP 8 (`public function __construct(public Service $service) {}`). Não crie construtores vazios.
- **Tipagem:** Exija declarações explícitas de tipo de retorno e de parâmetros em todos os métodos e funções.
- **Comentários e Enums:** Prefira blocos PHPDoc (com *array shapes* quando aplicável) ao invés de comentários *inline*. Chaves de Enums devem usar `TitleCase` (ex: `Monthly`).
- **Utilização de DTO:** Por favor, sempre que um método for receber mais de 2 parâmetros, prefira utilizar DTO para a transferência desses dados entre classes.
- **Service & Repository Pattern:** Utilize o padrão de projeto de Service Pattern para conter a lógica do endpoint e o Repository Pattern para isolar as consultas SQL (Eloquent ou nativas). As consultas devem residir exclusivamente nos repositories e serem chamadas através dos Services.
- **Injeção de dependência:** Para a Injeção de outros serviços e repositórios no escopo do método, pode ser utilizado o container do laravel `app(Service::class)`, evitando a declaração de muitos itens no `_construct()` da classe.

## 4. Arquitetura Laravel 12
- **Estrutura Moderna:** O projeto segue a estrutura simplificada do Laravel 11/12. Não crie ou procure arquivos na pasta `app/Http/Middleware/` ou `Console/Kernel.php`. Middlewares, roteamento e exceções devem ser registrados em `bootstrap/app.php`. Comandos de console em `app/Console/Commands/` são auto-registrados.
- **Validação e Controllers:** Sempre crie classes `Form Request` para validação (com mensagens de erro customizadas). Não valide inline nos controllers. Mantenha os controllers magros, delegando regras de negócio complexas para classes de `Service`.
- **APIs e Retorno de Dados:** Utilize Eloquent API Resources e versionamento para respostas. O retorno para o frontend **nunca deve ser a classe/modelo inteiro**, mas apenas o que for útil. O formato pode ser definido no retorno do Service ou, obrigatoriamente para listagens paginadas, via `JsonResource` para manter o padrão. Deve-se utilizar a `Response.php` trait (localizada em `app/Base/Traits/`) para padronizar o formato de resposta da API.
- **Tratamento de Exceções:** O `catch` deve ser capturado **apenas no controller**, utilizando a função `self::returnError($exception)` da `Response` trait para realizar o tratamento e retorno para o frontend.
- **Filas e Configurações:** Use jobs em fila (`ShouldQueue`) para tarefas demoradas. Use variáveis de ambiente apenas dentro de arquivos `config/` (ex: use `config('app.name')`, nunca `env()`).
- **Estrutura de Pacotes:** Por favor, sempre for fazer um módulo novo no sistema, verifique se já não possui um pacote adequado para a funcionalidade, caso possua, adicione o conteúdo dentro deste contexto. Os pacotes devem conter os itens separados por pastas.

## 5. Banco de Dados (PostgreSQL 17 & PgBouncer)
- **Performance e SQL Nativo:** O uso da facade `DB::` (como `DB::select` e `DB::selectOne`) é **altamente encorajado** para consultas analíticas, agregações complexas e relatórios. O foco principal deve ser a performance extrema utilizando a sintaxe avançada do PostgreSQL 17 (CTEs, Window Functions, manipulação avançada de JSONB). **Essas consultas devem ser obrigatoriamente implementadas em Repositories**, que serão injetados e chamados pelos Services.
- **Eloquent e N+1:** Quando utilizar o Eloquent para CRUDs tradicionais ou rotinas mais simples, garanta o uso rigoroso de *Eager Loading* (`with()`) para prevenir consultas N+1. Para limitar registros atrelados, use a sintaxe nativa (`$query->latest()->limit(10)`).
- **Transações e Consistência:** Em serviços que realizam múltiplas operações no banco, é essencial o uso de `DB::transaction()`. Não é necessário adicionar try/catch nos serviços ao usar `transaction()`, pois ele já lida com falhas e realiza o rollback automaticamente.
- **Utilização de Cache:** Sempre que houver listagens auxiliares ou dados que não mudam com frequência, deve-se utilizar a estrutura de cache para melhorar a performance e reduzir o estresse no banco de dados. Para isso, utilize a `CacheTrait` localizada em `app/Base/Traits/` (método `cache()`), que respeita a configuração `app_cache_active` do `.env`.
- **Migrations:** Ao modificar uma coluna, a migration deve incluir todos os atributos definidos anteriormente, ou eles serão perdidos.

## 6. Testes (Pest v4)
- **Regras Gerais:** Todos os testes devem ser escritos em Pest (`php artisan make:test --pest`). Não remova testes existentes sem aprovação explícita.
- **Sintaxe e Assertivas:** Utilize o padrão `it('faz algo', function () { ... });`. Use assertivas específicas como `assertForbidden()` em vez de `assertStatus(403)`.
- **Mocking e Datasets:** Use `Pest\Laravel\mock` para mocks parciais ou totais. Utilize fortemente a feature de `Datasets` (`->with([])`) para testar regras de validação sem duplicar código.
- **Browser Testing (Pest v4):** O Pest v4 é utilizado para testes *end-to-end* em `tests/Browser/`. Simule interações reais (`click`, `fill`, `assertSee`), intercepte notificações (`Notification::fake()`) e garanta ausência de erros no console (`assertNoJavascriptErrors()`).

## 7. Infraestrutura e Ambiente Local
- **Docker & Nginx:** O ambiente espelha a produção. Alterações nos arquivos `docker-compose.yml` ou nas configurações de bloco do Nginx (como limits de proxy, tratamento de timeout e roteamento para o PHP-FPM) devem ser tratadas com extrema cautela e priorizar a segurança.
- **Logs de Query:** O sistema possui um `AppServiceProvider` que salva logs de queries em arquivos separados baseados no endpoint. Respeite essa separação ao debugar métricas ou implementar novos rastreamentos de performance.

===

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4.18
- laravel/framework (LARAVEL) - v12
- laravel/horizon (HORIZON) - v5
- laravel/prompts (PROMPTS) - v0
- laravel/boost (BOOST) - v2
- laravel/mcp (MCP) - v0
- laravel/pail (PAIL) - v1
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- pestphp/pest (PEST) - v4
- phpunit/phpunit (PHPUNIT) - v12

## Skills Activation

This project has domain-specific skills available. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

- `pest-testing` — Tests applications using the Pest 4 PHP framework. Activates when writing tests, creating unit or feature tests, adding assertions, testing Livewire components, browser testing, debugging test failures, working with datasets or mocking; or when the user mentions test, spec, TDD, expects, assertion, coverage, or needs to verify functionality works.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan

- Use the `list-artisan-commands` tool when you need to call an Artisan command to double-check the available parameters.

## URLs

- Whenever you share a project URL with the user, you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain/IP, and port.

## Tinker / Debugging

- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.
- Use the `database-schema` tool to inspect table structure before writing migrations or models.

## Reading Browser Logs With the `browser-logs` Tool

- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)

- Boost comes with a powerful `search-docs` tool you should use before trying other approaches when working with Laravel or Laravel ecosystem packages. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic-based queries at once. For example: `['rate limiting', 'routing rate limiting', 'routing']`. The most relevant results will be returned first.
- Do not add package names to queries; package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'.
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit".
3. Quoted Phrases (Exact Position) - query="infinite scroll" - words must be adjacent and in that order.
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit".
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms.

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.

## Constructors

- Use PHP 8 constructor property promotion in `__construct()`.
    - `public function __construct(public GitHub $github) { }`
- Do not allow empty `__construct()` methods with zero parameters unless the constructor is private.

## Type Declarations

- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<!-- Explicit Return Types and Method Params -->
```php
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
```

## Enums

- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.

## Comments

- Prefer PHPDoc blocks over inline comments. Never use comments within the code itself unless the logic is exceptionally complex.

## PHPDoc Blocks

- Add useful array shape type definitions when appropriate.

=== tests rules ===

# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using the `list-artisan-commands` tool.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

## Database

- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries.
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `list-artisan-commands` to check the available options to `php artisan make:model`.

### APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## Controllers & Validation

- Always create Form Request classes for validation rather than inline validation in controllers. Include both validation rules and custom error messages.
- Check sibling Form Requests to see if the application uses array or string based validation rules.

## Authentication & Authorization

- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Queues

- Use queued jobs for time-consuming operations with the `ShouldQueue` interface.

## Configuration

- Use environment variables only in configuration files - never use the `env()` function directly outside of config files. Always use `config('app.name')`, not `env('APP_NAME')`.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== laravel/v12 rules ===

# Laravel 12

- CRITICAL: ALWAYS use `search-docs` tool for version-specific Laravel documentation and updated code examples.
- Since Laravel 11, Laravel has a new streamlined file structure which this project uses.

## Laravel 12 Structure

- In Laravel 12, middleware are no longer registered in `app/Http/Kernel.php`.
- Middleware are configured declaratively in `bootstrap/app.php` using `Application::configure()->withMiddleware()`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- The `app\Console\Kernel.php` file no longer exists; use `bootstrap/app.php` or `routes/console.php` for console configuration.
- Console commands in `app/Console/Commands/` are automatically available and do not require manual registration.

## Database

- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 12 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models

- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.

=== boost/core rules ===

# Laravel Boost

- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan

- Use the `list-artisan-commands` tool when you need to call an Artisan command to double-check the available parameters.

## URLs

- Whenever you share a project URL with the user, you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain/IP, and port.

## Tinker / Debugging

- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.
- Use the `database-schema` tool to inspect table structure before writing migrations or models.

## Reading Browser Logs With the `browser-logs` Tool

- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)

- Boost comes with a powerful `search-docs` tool you should use before trying other approaches when working with Laravel or Laravel ecosystem packages. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic-based queries at once. For example: `['rate limiting', 'routing rate limiting', 'routing']`. The most relevant results will be returned first.
- Do not add package names to queries; package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'.
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit".
3. Quoted Phrases (Exact Position) - query="infinite scroll" - words must be adjacent and in that order.
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit".
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms.

=== pint/core rules ===

# Laravel Pint Code Formatter

- You must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

## Pest

- This project uses Pest for testing. Create tests: `php artisan make:test --pest {name}`.
- Run tests: `php artisan test --compact` or filter: `php artisan test --compact --filter=testName`.
- Do NOT delete tests without approval.
- CRITICAL: ALWAYS use `search-docs` tool for version-specific Pest documentation and updated code examples.
- IMPORTANT: Activate `pest-testing` every time you're working with a Pest or testing-related task.

</laravel-boost-guidelines>
