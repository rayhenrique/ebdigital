<p align="center">
  <img src="public/images/logo-ad-transparent.png" alt="Logotipo Assembleia de Deus" width="220">
</p>

<h1 align="center">Caderneta EBD Online</h1>

<p align="center">
  <strong>Sistema Moderno, Multi-Tenant e Mobile-First de Gestão e Apuração da Escola Bíblica Dominical</strong><br>
  Igreja Evangélica Assembleia de Deus
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 13">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/Livewire-v3-FB70A9?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.4+-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white" alt="Alpine.js">
  <img src="https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Tests-58%20Passing%20(100%25)-success?style=for-the-badge" alt="Testes Aprovados">
</p>

---

## 📖 Sobre o Projeto

A **Caderneta EBD Online** foi concebida para substituir de forma definitiva as tradicionais cadernetas de papel das Escolas Bíblicas Dominicais da **Igreja Evangélica Assembleia de Deus**. O sistema atende desde igrejas locais únicas até campos eclesiásticos inteiros com múltiplas congregações:

- ❌ **Fim do retrabalho manual**: Elimina a necessidade de a secretaria recolher cadernetas físicas no fim da aula e calcular manualmente somas de presenças e ofertas.
- ⛪ **Arquitetura Multi-Tenant Nativa**: Gestão unificada de múltiplas congregações (Templo Sede e congregações filiadas), com isolamento rigoroso de dados entre secretarias e visão consolidada para a liderança geral.
- ⏱️ **Apuração em Tempo Real**: Conforme os professores realizam a chamada em suas salas pelo celular, o **Dashboard Consolidado da Secretaria** atualiza instantaneamente a contagem geral de alunos, visitantes, bíblias, revistas e ofertas em dinheiro.
- 📊 **Inteligência Pedagógica & Cuidado Pastoral**: Relatórios analíticos por trimestre, identificação automática de alunos com faltas consecutivas (visitação pastoral) e integração com WhatsApp para felicitações de aniversariantes.
- 📱 **Design Híbrido Mobile-First & Tablet**: Interface otimizada para smartphones com barra inferior fixa, além de **Sidebar Recolhível (Collapse)** com apenas ícones para máxima área útil em tablets (orientação vertical/horizontal) e computadores.
- 🔒 **Rastreabilidade e Segurança**: Controle de acesso baseado em papéis (RBAC) com trilha de auditoria completa em formato JSON para todas as operações críticas.

---

## 👥 Perfis de Acesso e Permissões (RBAC)

O sistema conta com 3 níveis rígidos de acesso gerenciados pelo middleware `CheckRole`, `TenantService` e Policies do Laravel:

| Perfil | Escopo de Acesso | Funcionalidades Principais |
| :--- | :--- | :--- |
| 👑 **Administrador (Pastor / Liderança Geral)** | Todo o Campo Eclesiástico | Alternância entre "Todas as Congregações" ou congregações específicas, CRUD de Congregações, Gestão Geral de Usuários, redefinição rápida de senhas, auditoria com expurgo e relatórios consolidados do campo. |
| 📋 **Secretário / Superintendente** | Congregação Própria | Dashboard Consolidado em tempo real, retificação de chamadas retroativas, cadastro de turmas, alunos e professores da sua congregação, e relatórios analíticos/pastorais. |
| 📖 **Professor** | Sala de Aula | Realização da chamada dominical das turmas vinculadas ao seu usuário na data corrente (bloqueio automático de retroatividade). |

---

## 🚀 Principais Funcionalidades

### 1. Arquitetura Multi-Tenant (Congregações)
- **Isolamento de Dados por Congregação**: Utilização do trait `BelongsToCongregation` e escopo global `CongregationScope`, garantindo que secretários e professores acessem estritamente dados da sua respectiva congregação.
- **Alternador Dinâmico de Contexto (Tenant Switcher)**: Dropdown interativo na barra lateral permitindo ao Administrador alternar instantaneamente entre congregações ou selecionar o modo geral *"Todas as Congregações"*.
- **Modo Global Somente Leitura (Proteção de CRUD)**: Quando o Administrador visualiza *"Todas as Congregações"*, operações de escrita nos módulos de Classes, Alunos, Professores e Chamadas são desativadas de forma transparente, prevenindo registros órfãos ou inconsistências de tenant.

### 2. Módulo de Relatórios da EBD (`/relatorios`)
- **Relatório Consolidado (Trimestral e Mensal)**:
  - Atalhos de 1 clique para *Este Mês*, *1º Trimestre*, *2º Trimestre*, *3º Trimestre*, *4º Trimestre* e *Ano Vigente*.
  - Indicadores-chave: Média por domingo, taxa de assiduidade global, total de visitantes, bíblias, revistas e ofertas totais.
  - Tabela comparativa por classe com classificação visual de desempenho (*verde >= 75%*, *amarelo >= 50%*, *vermelho < 50%*).
- **Frequência Nominal & Alerta de Faltosos Crônicos**:
  - Grade visual aula a aula com presença (`P`) e falta (`F`).
  - Destaque automático para alunos com **3 ou mais faltas seguidas** com recomendação expressa para visitação pastoral.
- **Aniversariantes do Mês com Felicitação WhatsApp**:
  - Filtro por mês com cálculo automático da idade.
  - Botão de 1 toque **"Parabenizar (WhatsApp)"** com mensagem pastoral pré-formatada.
- **Modo de Impressão Oficial A4 / PDF**:
  - Layout limpo e padronizado com timbre oficial da **Assembleia de Deus**, dados de emissão e campos de assinatura para o Pastor e Superintendente da EBD.

### 3. Chamada Dominical Mobile-First (Livewire)
- **Checklist Ergonômico**: Alvos de toque de no mínimo 48px para cada aluno, com marcação de presença, porte de bíblia e posse de revista.
- **Ações em Lote**: Botões de 1 clique para *"Marcar Todos Presentes"* e *"Desmarcar Todos"*.
- **Barra Flutuante Inferior Fixa (Mobile)**: Permite ao professor acompanhar a taxa de presença em tempo real e tocar em **`💾 Gravar Aula`** sem precisar rolar até o fim de listas longas.
- **Transação Atômica**: Toda chamada roda sob `DB::transaction()`, gravando `lesson_records` e `lesson_attendances` simultaneamente com registro em auditoria.

### 4. Dashboard Consolidado em Tempo Real
- **Cabeçalho Dinâmico**: Exibição da congregação ativa e contador dinâmico de status (*"X de Y Classes Entregues"*).
- **Seletor de Data de Referência**: Calendário reativo para navegação entre domingos anteriores ou atuais.
- **Grid de 6 KPIs**: Alunos Presentes, Visitantes, Bíblias, Revistas, Total Geral da EBD e Total de Ofertas (R$).
- **Quadro de Classes Híbrido**: Cards sanfonados no celular e tabela gerencial no computador/tablet.

### 5. Sidebar Desktop & Tablet com Modo Recolhível (Collapse)
- **Modo Compacto (Apenas Ícones)**: Redução de `w-[268px]` para `w-20` (80px), centralizando ícones com área de toque mínima de 44px e tooltips nativos em hover.
- **Auto-detecção para Tablets**: Inicialização automática no modo recolhido em telas menores que 1024px (iPads e tablets em posição vertical), maximizando o espaço útil para gráficos e tabelas.
- **Persistência Local**: A preferência do usuário é salva no `localStorage` (`ebd_sidebar_collapsed`) e preservada na navegação SPA com Livewire (`wire:navigate`).
- **Scrollbar Suave e Ultrafina**: Barra de rolagem personalizada de 4px (`sidebar-scroll`), eliminando barras cinzas grossas do Windows.

### 6. Gestão Descentralizada de Professores (`/professores`)
- Permite ao Secretário e Superintendente cadastrar novos professores, editar dados, ativar/inativar contas e **redefinir senhas** dos professores da sua própria congregação sem depender do Administrador geral.

### 7. Trilha de Auditoria & Governança (Audit Logs)
- Registro imutável de todas as mutações relevantes do sistema na tabela `audit_logs` (criação, edição, chamadas, redefinição de senhas, ativação/desativação).
- Armazenamento estruturado de `user_id`, `congregation_id`, `ip_address`, `user_agent`, `payload_before` e `payload_after` em JSON.
- **Rotina de Limpeza Automática**: Comando Artisan `php artisan audit:prune --days=90` agendado diariamente em `routes/console.php`.
- **Ação Manual do Administrador**: Botão de expurgo manual para descartar registros com mais de 30 dias.

---

## 🛠️ Tecnologias Utilizadas

- **Backend**: [Laravel 13.x](https://laravel.com/) (Framework PHP)
- **Linguagem**: PHP 8.2+ com tipagem estrita ativa (`declare(strict_types=1);`)
- **Frontend Reativo**: [Livewire v3](https://livewire.laravel.com/) e [Alpine.js 3.x](https://alpinejs.dev/)
- **Estilização**: [Tailwind CSS 3.4+](https://tailwindcss.com/) com design system sob medida
- **Autenticação**: Laravel Breeze adaptado para múltiplos perfis com Blade e Tailwind
- **Tipografia**: Google Fonts (*Plus Jakarta Sans* e *Inter*)
- **Banco de Dados**: MySQL 8.0+ / MariaDB
- **Build Tool**: [Vite 7.x](https://vitejs.dev/)
- **Testes**: PHPUnit integrado ao Laravel (58 testes automatizados)

---

## 🏛️ Estrutura Arquitetural do Código

```
ebdigital/
├── app/
│   ├── Enums/
│   │   └── UserRole.php                   # Enum tipado com os perfis (Admin, Secretario, Professor)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── AuditLogController.php # Consulta e expurgo de logs
│   │   │   │   ├── CongregationController.php # Gestão de congregações
│   │   │   │   └── UserController.php     # Gestão geral de usuários e senhas
│   │   │   ├── AttendanceController.php   # Acesso ao módulo de chamada
│   │   │   ├── ProfileController.php      # Perfil do usuário conectado
│   │   │   └── Secretaria/
│   │   │       ├── ClassController.php    # Gestão de turmas
│   │   │       ├── ReportController.php   # Controlador de relatórios
│   │   │       ├── StudentController.php  # Cadastro de alunos
│   │   │       └── TeacherController.php  # Gestão de professores da congregação
│   │   ├── Middleware/
│   │   │   └── CheckRole.php              # Proteção de rotas por perfis e bloqueio de inativos
│   │   └── Requests/                      # Validações estruturadas via Form Requests
│   ├── Livewire/
│   │   ├── DailyConsolidatedDashboard.php # Componente reativo do Dashboard Geral
│   │   ├── Reports/
│   │   │   └── EbdReports.php             # Componente reativo dos Relatórios da EBD
│   │   └── TakeAttendance.php             # Componente reativo da Chamada Dominical
│   ├── Models/
│   │   ├── AuditLog.php                   # Model de auditoria
│   │   ├── Congregation.php               # Model de congregações
│   │   ├── EbdClass.php                   # Model de turmas (tabela `classes`)
│   │   ├── LessonAttendance.php           # Model de presença individual do aluno
│   │   ├── LessonRecord.php               # Model do cabeçalho da aula e fechamento
│   │   ├── Student.php                    # Model de alunos
│   │   ├── User.php                       # Model de usuários com helpers de permissão
│   │   ├── Scopes/
│   │   │   └── CongregationScope.php      # Escopo global multi-tenant
│   │   └── Traits/
│   │       └── BelongsToCongregation.php  # Trait com preenchimento e escopo automático
│   ├── Policies/
│   │   └── LessonRecordPolicy.php         # Regras de permissão de chamada
│   └── Services/
│       ├── AuditService.php               # Serviço desacoplado de auditoria
│       └── TenantService.php              # Gerenciador de contexto de congregações
├── database/
│   ├── migrations/                        # Estrutura relacional do banco de dados
│   └── seeders/DatabaseSeeder.php         # Povoamento inicial com congregações, usuários e turmas
├── lang/pt_BR/                            # Localização 100% em Português do Brasil
├── resources/
│   ├── css/app.css                        # Folha de estilos com Tailwind CSS e scrollbars customizadas
│   ├── js/app.js                          # Configuração de scripts e Alpine.js
│   └── views/                             # Templates Blade organizados por domínio
├── routes/
│   ├── console.php                        # Agendamento diário de expurgo de auditoria
│   └── web.php                            # Definição de rotas web agrupadas por perfil
└── tests/                                 # 58 testes automatizados cobrindo o fluxo completo
```

---

## ⚙️ Instalação Passo a Passo

### 1. Clonar o Repositório
```bash
git clone https://github.com/rayhenrique/ebdigital.git
cd ebdigital
```

### 2. Instalar Dependências do PHP
```bash
composer install
```

### 3. Instalar Dependências do Frontend
```bash
npm install
```

### 4. Configurar as Variáveis de Ambiente
Copie o arquivo `.env.example` para `.env`:
```bash
cp .env.example .env
```

Abra o arquivo `.env` e configure o banco de dados MySQL e o timezone:
```env
APP_NAME="Caderneta EBD Online"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=America/Maceio
APP_URL=http://127.0.0.1:8000
APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=pt_BR

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ebdigital
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Gerar a Chave da Aplicação
```bash
php artisan key:generate
```

### 6. Criar o Banco e Executar as Migrações com Dados Iniciais
```bash
php artisan migrate:fresh --seed
```

### 7. Compilar os Recursos do Frontend
```bash
npm run build
```

### 8. Iniciar o Servidor Local
```bash
php artisan serve
```
O sistema estará disponível em: **`http://127.0.0.1:8000`**

---

## 🔐 Credenciais de Acesso para Demonstração

O seeder padrão (`DatabaseSeeder`) cria as seguintes contas para teste imediato de todos os fluxos:

| Perfil | E-mail | Senha Padrão | Congregação Vinculada |
| :--- | :--- | :--- | :--- |
| **Pastor / Administrador** | `admin@ebd.local` | `senha123` | Acesso Geral / Templo Sede |
| **Secretário / Superintendente** | `secretario@ebd.local` | `senha123` | Templo Sede |
| **Professor 1 (Prof. Barnabé Silva)** | `professor1@ebd.local` | `senha123` | Templo Sede (*Adultos e Jovens*) |
| **Professor 2 (Profa. Débora Oliveira)** | `professor2@ebd.local` | `senha123` | Templo Sede (*Adolescentes e Infantil*) |

> 💡 *Dica: A tela de login possui chips rápidos na parte inferior que preenchem as credenciais de qualquer perfil com apenas 1 clique.*

---

## 🧪 Testes Automatizados

A aplicação conta com uma suíte abrangente de **58 testes automatizados (246 asserções)** que garantem a estabilidade das regras de negócio, segurança multi-tenant e integridade das transações.

Para rodar todos os testes:
```bash
php artisan test
```

### Cobertura de Testes:
- **`MultiTenantTest`**: Garante o isolamento completo entre congregações, alternância de contexto do Administrador, restrição de gestão de professores à congregação do secretário e bloqueio de operações de escrita no modo global.
- **`ReportsTest`**: Valida o acesso restrito a administradores e secretários, cálculo de métricas consolidadas, atalhos de períodos e apuração de aniversariantes.
- **`TakeAttendanceTransactionTest`**: Garante atomicidade em `DB::transaction()` na gravação de presenças e histórico de auditoria.
- **`AttendancePolicyTest`**: Garante o bloqueio de chamadas retroativas para professores e permissão para secretários.
- **`AdminUserPasswordResetTest`**: Valida a redefinição de senhas protegida com `403 Forbidden` para não-administradores.
- **`CrudDestroyTest`**: Protege contra a exclusão de turmas ou alunos com histórico financeiro/presencial.
- **`AuditPruneTest`**: Valida o comando de expurgo automático de logs antigos (90 dias) e expurgo manual (30 dias).
- **Testes de Autenticação e Perfil**: Validação completa dos fluxos de login, recuperação de senha e segurança de conta.

---

## 🛡️ Diretrizes de Engenharia e Boas Práticas

- **Strict Types**: Todos os arquivos PHP do projeto utilizam `declare(strict_types=1);`.
- **Atomicidade Transacional**: Operações envolvendo múltiplas tabelas executam estritamente sob `DB::transaction()`.
- **Prevenção de Duplicidades**: Concorrência bloqueada em nível de banco através de índices compostos únicos (`class_id` + `lesson_date`).
- **Nomenclatura Semântica**: Models no singular PascalCase (ex: `EbdClass`, `Congregation`), tabelas no plural snake_case (`classes`, `congregations`, `lesson_records`).
- **UI/UX Touch Target**: Alvos de toque nunca inferiores a 44px-48px, em total conformidade com as diretrizes do Google Material e Apple HIG.

---

## 📄 Licença

Este projeto é desenvolvido e mantido para a **Igreja Evangélica Assembleia de Deus**. Todos os direitos reservados.
