# Product Requirement Document (PRD) - Caderneta EBD Online

## 1. Visão Geral do Produto & Problema
O sistema substitui as cadernetas físicas de papel da Escola Bíblica Dominical (EBD) por uma aplicação web responsiva (PWA/Mobile-First). Resolve os problemas de perda de dados, demora na apuração das estatísticas gerais no final da aula, rasuras e retrabalho de digitação pela secretaria da igreja.

## 2. Matriz de Perfis e Permissões (RBAC)
O sistema possui 3 papéis (`roles`):
* **Admin (`admin`):**
  * Gerenciamento completo de usuários (criar, editar, ativar/desativar, redefinir senhas).
  * Visualização de logs de auditoria do sistema.
  * Execução da rotina de limpeza manual de auditoria (manter últimos 30 dias).
* **Secretário/Superintendente (`secretario`):**
  * Cadastro e gerenciamento de Classes/Turmas e Alunos.
  * Atribuição de Professores às Classes.
  * Visualização do painel geral consolidado de todas as classes em tempo real.
  * Retificação retroativa de quaisquer chamadas passadas sem travas de data.
  * Exportação e consulta de relatórios consolidados por data.
* **Professor (`professor`):**
  * Visualização apenas das classes em que está formalmente vinculado.
  * Registro de frequência dos alunos matriculados na data corrente da aula.
  * Preenchimento dos dados estatísticos agregados de rodapé (Bíblias, Revistas, Ofertas, Visitantes).
  * Bloqueado contra alteração de aulas de datas retroativas (permissão exclusiva do Secretário/Admin).

## 3. Requisitos Funcionais

### Módulo de Autenticação & Usuários
* **RF01:** Autenticação por e-mail e senha.
* **RF02:** Gestão administrativa de usuários (CRUD + Reset de Senha) exclusivo do Admin.

### Módulo Cadastral (Secretaria)
* **RF03:** CRUD de Classes (Nome da turma, descrição, status ativa/inativa).
* **RF04:** CRUD de Alunos (Nome completo, telefone, data de nascimento, classe vinculada, status ativo/inativo).
* **RF05:** Vínculo de Professores x Classes (relação N:N).

### Módulo de Lançamento de Chamada (Mobile-First / Livewire)
* **RF06:** Seleção da data da aula (default: data atual, permitindo flexibilidade de dias da semana).
* **RF07:** Lista dinâmica de alunos com toggle touch rápido de `Presente` / `Ausente`.
* **RF08:** Inputs consolidados de rodapé (Visitantes, Bíblias, Revistas, Ofertas em R$, Observações).
* **RF09:** Cálculo visual em tempo real no cabeçalho/rodapé (Total Matriculados, Presentes, Total Geral).
* **RF10:** Trava de concorrência e unicidade: Apenas uma chamada registrada por classe na mesma data.

### Módulo Painel Consolidado & Secretaria
* **RF11:** Dashboard consolidado da data selecionada exibindo status das classes (Entregue/Pendente) e totalizadores gerais da congregação.
* **RF12:** Edição retroativa: Interface exclusiva para Secretário/Admin editar registros de datas passadas.

### Módulo de Auditoria & Governança
* **RF13:** Registro background de ações críticas (login, criação/edição de chamadas, retificações, exclusões).
* **RF14:** Retenção programada: Expurgar automaticamente logs > 90 dias via Scheduled Task.
* **RF15:** Purge manual: Ação administrativa para manter apenas os últimos 30 dias.

## 4. Requisitos Não-Funcionais
* **RNF01 (Stack):** Laravel 13.x, PHP 8.2+, MySQL 8.0+.
* **RNF02 (Performance/UX):** Resposta das marcações via Livewire sem recarregar a tela (Mobile-first, touch targets de 48px).
* **RNF03 (Integridade):** Salvamento da chamada e presenças obrigatoriamente via transação de banco (`DB::transaction`).