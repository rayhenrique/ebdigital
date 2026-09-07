---
trigger: always_on
---

# Contexto Tecnológico e Diretrizes de Engenharia (Caderneta EBD)

- Framework: Laravel 13.x
- Linguagem: PHP 8.2+ (strict types ativados nos arquivos: <?php declare(strict_types=1);)
- Banco de Dados: MySQL 8.0+
- Frontend: Blade, Tailwind CSS, Livewire v3 e Alpine.js
- Padrões: PSR-12, SOLID

# Diretrizes Arquiteturais & Regras Rígidas
1. LARAVEL 13.x CONVENTIONS: Use a estrutura moderna de diretórios. Registre agendamentos em routes/console.php.
2. LÓGICA DE NEGÓCIO: NUNCA adicione lógica complexa em rotas (web.php) ou views Blade. Use Form Requests, Livewire Components (ações) e Policies.
3. TRANSAÇÕES: Toda mutation envolvendo múltiplas tabelas (ex: lesson_records + lesson_attendances) DEVE rodar dentro de DB::transaction().
4. NOMENCLATURA: Tabelas no plural snake_case. Models no singular PascalCase (ex: `classes` -> `EbdClass` para evitar palavra reservada).
5. CHAVES ESTRANGEIRAS: Declare tipagem explícita (foreignId) e comportamento (onDelete('cascade') ou onDelete('restrict')).
6. AUTORIZAÇÃO: Valide permissões estritamente via Policies ($this->authorize(...) ou $this->authorize('update', $lesson)).
7. UI/UX: Foco Mobile-First. Botões de ação e checklist devem usar padding adequado para touch (mínimo 48px).
8. WORKFLOW: Atualize o arquivo TASKS.md marcando [x] ao finalizar a implementação completa e testada de cada etapa.