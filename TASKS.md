# Checklist de Implementação - Caderneta EBD (Laravel 13.x)

## Fase 1: Setup, Migrations & Database
- [x] 1.1 Iniciar projeto Laravel 13.x e instalar Laravel Breeze (Blade/Alpine) + Livewire v3.
- [x] 1.2 Configurar `.env` para MySQL e timezone `America/Maceio`.
- [x] 1.3 Criar migration de `users` (add `role`, `is_active`).
- [x] 1.4 Criar migrations para `classes`, `class_teacher` e `students`.
- [x] 1.5 Criar migrations para `lesson_records`, `lesson_attendances` e `audit_logs`.
- [x] 1.6 Implementar `DatabaseSeeder` com usuários, classes e alunos de teste.

## Fase 2: Models, Relacionamentos & Casts
- [x] 2.1 Model `User`: casts (`role` enum), relações.
- [x] 2.2 Model `EbdClass`: relações `teachers`, `students`, `lessonRecords`.
- [x] 2.3 Model `Student`: scope de ativos, relação com classe.
- [x] 2.4 Model `LessonRecord`: casts (`lesson_date` => 'date', `offerings_amount` => 'decimal:2').
- [x] 2.5 Model `AuditLog` e criação de um `AuditService::log()` reaproveitável.

## Fase 3: Autenticação, Middlewares & Policies
- [x] 3.1 Implementar Gate/Middleware de verificação de Roles (`CheckRole`).
- [x] 3.2 Implementar `LessonRecordPolicy` (Regra: Professor só edita a data atual; Secretário edita tudo).
- [x] 3.3 Ajustar redirecionamento pós-login baseado no `role`.

## Fase 4: Módulo Administrativo & Secretaria
- [x] 4.1 CRUD de Usuários (Apenas Admin).
- [x] 4.2 CRUD de Classes e atribuição de Professores (Livewire ou Controller).
- [x] 4.3 CRUD de Alunos vinculado às classes.
- [x] 4.4 Console Command `audit:prune` no Scheduler do Laravel 13 (`routes/console.php`).
- [x] 4.5 Action/Rota administrativa para purge manual de auditoria (30 dias).

## Fase 5: Módulo de Chamada Mobile-First (Livewire)
- [x] 5.1 Criar componente `TakeAttendance` (UI Mobile-friendly, botões de toque grandes).
- [x] 5.2 Implementar persistência da aula usando `DB::transaction()` no método save.
- [x] 5.3 Garantir atualização reativa do sumário (Matriculados, Presentes, Total).
- [x] 5.4 Bloqueio visual se a aula já foi submetida em data retroativa (para professores).

## Fase 6: Dashboard Consolidado
- [x] 6.1 Criar componente `DailyConsolidatedDashboard` filtrável por data (`lesson_date`).
- [x] 6.2 Somatório de métricas: Total Alunos, Visitantes, Bíblias, Revistas, Oferta em R$.
- [x] 6.3 Habilitar modo de edição do Secretário (reaproveitando componente de chamada + Auditoria).

## Fase 7: Testes
- [x] 7.1 Teste: Bloqueio de edição retroativa por professores.
- [x] 7.2 Teste: Integridade da transação de chamada.
- [x] 7.3 Teste: Expurgo de logs no comando artisan.
