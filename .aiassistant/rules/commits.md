---
apply: always
---

# Regras para Geração de Commits (Padrão Conventional Commits)

Você deve atuar como um desenvolvedor Sênior ao redigir mensagens de commit. Todas as mensagens devem seguir o padrão Conventional Commits e ser escritas em **Português do Brasil (PT-BR)**.

## 1. Estrutura do Commit
A mensagem deve seguir o formato:
`<tipo>(<escopo>): <descrição curta>`

- **Tipo:** Define a intenção da mudança (lista abaixo).
- **Escopo (Opcional):** Um substantivo que represente a parte do sistema afetada (ex: auth, database, checkout, api).
- **Descrição:** Um resumo conciso da alteração, iniciando com letra minúscula e sem ponto final.

## 2. Tipos Permitidos
Use estritamente os seguintes tipos:
- **feat:** Uma nova funcionalidade ou recurso.
- **fix:** Correção de um erro (bug).
- **docs:** Alterações apenas na documentação (README, comentários).
- **style:** Mudanças que não afetam o sentido do código (espaços, formatação, vírgulas).
- **refactor:** Uma alteração no código que não corrige erro nem adiciona funcionalidade (melhoria de legibilidade/estrutura).
- **perf:** Mudança de código focada em melhorar o desempenho.
- **test:** Adição de testes ausentes ou correção de testes existentes.
- **build:** Mudanças que afetam o sistema de build ou dependências externas (ex: composer, npm, docker).
- **ci:** Mudanças em arquivos de configuração de CI/CD (ex: GitHub Actions, GitLab CI).
- **chore:** Outras mudanças que não modificam arquivos de código ou de teste (ex: .gitignore).
- **revert:** Quando o commit reverte um commit anterior.

## 3. Regras Gramaticais e Estilo
- **Linguagem:** Português do Brasil (PT-BR).
- **Verbos:** Use o imperativo ou o infinitivo para descrever a ação (ex: "adiciona", "corrige", "refatora" ou "adicionar", "corrigir"). Seja consistente.
- **Tamanho:** A primeira linha não deve ultrapassar 72 caracteres.
- **Corpo (Opcional):** Se a mudança for complexa, pule uma linha após a primeira e descreva detalhadamente o "porquê" e o "como", também em PT-BR.

## 4. Exemplos de Commits Corretos
- feat(auth): implementar autenticação via JWT
- fix(database): corrigir erro de conexão no pgbouncer
- perf(api): otimizar query de busca de usuários
- docs: atualizar instruções de instalação no README
- chore(deps): atualizar versão do laravel/framework para 11.x
- build(docker): ajustar limites de memória no docker-compose.yml

## 5. Instrução para a IA
Ao ser solicitado para "gerar um commit" ou "comitar as alterações":
1. Analise o `git diff` das alterações.
2. Identifique o tipo predominante.
3. Identifique o escopo mais relevante.
4. Gere a mensagem seguindo as regras acima, sem explicações adicionais, apenas o texto do commit.
