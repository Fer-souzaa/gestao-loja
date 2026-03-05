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
