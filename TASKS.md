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

## Fase 8: Módulo de Relatórios da EBD
- [x] 8.1 Controller `ReportController` e rota `/relatorios` protegida para Admin e Secretário.
- [x] 8.2 Componente Livewire `EbdReports` com filtros de período (mês, trimestres, ano).
- [x] 8.3 Relatório Consolidado Trimestral/Mensal com cards analíticos e tabela comparativa por classe.
- [x] 8.4 Relatório de Frequência Nominal com histórico de presença/falta e alerta de faltosos crônicos (3+ faltas seguidas para visitação pastoral).
- [x] 8.5 Relatório de Aniversariantes do mês com cálculo de idade e botão de felicitação direta via WhatsApp.
- [x] 8.6 Layout de Impressão Oficial A4/PDF (`@media print`) com timbre da Assembleia de Deus e campos de assinatura (Pastor e Superintendente).
- [x] 8.7 Testes automatizados cobrindo acesso, cálculo de métricas, atalhos de data e aniversariantes (`ReportsTest`).

## Fase 9: Refatoração Multi-Tenant (Congregações)
- [x] 9.1 Migrations: criação da tabela `congregations` e adição de `congregation_id` com chaves estrangeiras em `users`, `classes`, `students`, `lesson_records` e `audit_logs`.
- [x] 9.2 Models & Global Scopes: Model `Congregation`, serviço `TenantService`, escopo global `CongregationScope` e Trait `BelongsToCongregation` com preenchimento automático.
- [x] 9.3 Gestão de Congregações: Controller `CongregationController`, Form Requests, rotas `/admin/congregacoes` e views Blade (Index, Create, Edit).
- [x] 9.4 Alternador de Contexto (Tenant Switcher): Dropdown interativo na Sidebar desktop e Gaveta mobile para o Admin alternar entre "Todas as Congregações" ou congregações específicas em tempo real via sessão.
- [x] 9.5 Módulo de Gestão de Professores: Controller `TeacherController`, Form Requests, rotas `/professores` e views Blade permitindo ao Secretário/Superintendente cadastrar, editar, ativar/inativar e resetar senha dos professores da sua própria congregação com isolamento total.
- [x] 9.6 Atualização dos Módulos Existentes: Escopo aplicado em Usuários, Classes, Alunos, Chamada (`TakeAttendance`), Dashboard e Relatórios (`EbdReports`).
- [x] 9.7 Seeders & Testes Automatizados: Migração inicial e testes no `MultiTenantTest` garantindo 100% de cobertura e integridade (55 testes passando).
## Fase 10: Otimização de Interface & Sidebar Recolhível (Tablet/Desktop)
- [x] 10.1 Sidebar com Modo Recolhível (Collapse): Transição dinâmica entre largura total (`w-[268px] lg:w-72`) e modo compacto (`w-20`) com apenas ícones.
- [x] 10.2 Persistência e Auto-detecção Responsiva: Detecção automática do modo recolhido para tablets em orientação vertical (`< 1024px`) e persistência da preferência do usuário via `localStorage` (`ebd_sidebar_collapsed`).
- [x] 10.3 Acessibilidade e UX Touch: Botões de alternar recolher/expandir no cabeçalho e rodapé da barra lateral, com touch targets adequados (44px) e tooltips nativos em todos os itens.
- [x] 10.4 Estilização Refinada: Barra de rolagem suave e fina (`sidebar-scroll`), alinhamento centralizado de avatares/ícones e adaptação do indicador de congregação ativa no modo recolhido.

## Fase 11: Conformidade Google Play & Identidade Visual
- [x] 11.1 Política de Privacidade Oficial (`/politica-de-privacidade`) com dados do app (`br.com.adteotoniovilela.cadernetaebd`) e desenvolvedor (Ray Henrique) em total conformidade com a Google Play.
- [x] 11.2 Links de navegação pública e interna para a Política de Privacidade (tela de login, sidebar desktop e gaveta mobile).
- [x] 11.3 Créditos do Desenvolvedor: Link oficial no rodapé da tela de login apontando para a KL Tecnologia (`https://kltecnologia.com`).

## Fase 12: Unificação do Dashboard Central Pós-Login
- [x] 12.1 Redirecionamento padronizado pós-login para a tela central de Dashboard (`/dashboard`) para todos os perfis (Admin, Secretário e Professor), vinculado ou não a congregação.
- [x] 12.2 Rota unificada `/dashboard` renderizando o `daily-consolidated-dashboard` com retrocompatibilidade de redirecionamento para `/secretaria/dashboard`.
- [x] 12.3 Atualização dos menus de navegação (desktop sidebar, mobile sidebar e mobile bottom bar) com acesso universal ao Dashboard Geral e permissões contextuais de chamada.
- [x] 12.4 Tratamento seguro no `TenantService` para usuários sem congregação vinculada e cobertura em testes automatizados (`AuthenticationTest`).

## Fase 13: Gestão e Matrícula de Alunos por Professores
- [x] 13.1 Criar a `StudentPolicy` para autorização de acesso a alunos (Admin, Secretário e Professor com escopo estrito às suas turmas).
- [x] 13.2 Form Requests (`StoreStudentRequest`, `UpdateStudentRequest`) atualizados para validar autorização do professor sobre sua classe.
- [x] 13.3 Controlador `StudentController` adaptado para permitir que professores listem, cadastrem, editem e ativem/inativem alunos de suas próprias turmas.
- [x] 13.4 Componente Livewire `TakeAttendance` enriquecido com botão e modal de Matrícula Rápida em sala de aula (com inclusão automática e presença marcada).
- [x] 13.5 Menus de navegação (sidebar desktop, gaveta mobile e barra inferior mobile) atualizados com acesso direto de professores a "Alunos".
- [x] 13.6 Suíte completa de testes automatizados (`StudentManagementTest`) garantindo isolamento entre turmas e proteção contra acessos indevidos (73 testes passando).



