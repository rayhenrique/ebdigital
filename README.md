<p align="center">
  <img src="public/images/logo-ad-transparent.png" alt="Logotipo Assembleia de Deus" width="220">
</p>

<h1 align="center">Caderneta EBD Online</h1>

<p align="center">
  <strong>Sistema Moderno e Mobile-First de Gestão e Apuração da Escola Bíblica Dominical</strong><br>
  Igreja Evangélica Assembleia de Deus
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 13">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/Livewire-v4-FB70A9?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.4+-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white" alt="Alpine.js">
  <img src="https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Tests-35%20Passing%20(100%25)-success?style=for-the-badge" alt="Testes Aprovados">
</p>

---

## 📖 Sobre o Projeto

A **Caderneta EBD Online** foi concebida para substituir de forma definitiva as tradicionais cadernetas de papel das Escolas Bíblicas Dominicais. O sistema resolve as maiores dores da administração e do corpo docente:

- ❌ **Fim do retrabalho manual**: Elimina a necessidade de a secretaria recolher cadernetas físicas no fim da aula e calcular manualmente somas de presenças e ofertas.
- ⏱️ **Apuração em Tempo Real**: Conforme os professores realizam a chamada em suas salas pelo celular, o **Dashboard Consolidado da Secretaria** atualiza instantaneamente a contagem geral de alunos, visitantes, bíblias, revistas e ofertas em dinheiro.
- 📱 **Experiência 100% Mobile-First**: Interface ultra-otimizada para smartphones (largura fluida, alvos de toque mínimos de 48px, barra inferior fixa e navegação ergonômica).
- 🔒 **Rastreabilidade e Segurança**: Controle de acesso baseado em papéis (RBAC) com trilha de auditoria completa em formato JSON para todas as operações críticas.

---

## 👥 Perfis de Acesso e Permissões (RBAC)

O sistema conta com 3 níveis rígidos de acesso gerenciados pelo middleware `CheckRole` e por Policies do Laravel:

| Perfil | Escopo de Acesso | Funcionalidades Principais |
| :--- | :--- | :--- |
| 👑 **Administrador (Pastor / Liderança)** | Acesso Irrestrito | Gestão de Usuários, redefinição rápida de senhas, consulta e expurgo de logs de auditoria, acesso a turmas, alunos e dashboard. |
| 📋 **Secretário / Superintendente** | Gestão da EBD | Dashboard Consolidado em tempo real, retificação de chamadas retroativas, cadastro de turmas e alunos, relatórios gerais. |
| 📖 **Professor** | Sala de Aula | Realização da chamada dominical das turmas vinculadas ao seu usuário na data corrente (bloqueio automático de retroatividade). |

---

## 🚀 Principais Funcionalidades

### 1. Tela de Autenticação Premium
- Identidade visual institucional com o emblema oficial da **Assembleia de Deus**.
- Cartão responsivo com degradê sutil e iluminação difusa (*ambient blur glow*).
- Inputs modernos com ícones internos à esquerda (e-mail e cadeado) e botão interativo para **alternar visibilidade da senha (olho / olho cortado)** com Alpine.js.
- Chips de preenchimento rápido em 1 toque para testes e demonstrações: `Pastor (Admin)`, `Secretário` e `Professor`.

### 2. Chamada Dominical Mobile-First (Livewire)
- **Checklist Ergonômico**: Alvos de toque de no mínimo 48px para cada aluno, com marcação de presença, porte de bíblia e posse de revista.
- **Ações em Lote**: Botões de 1 clique para *"Marcar Todos Presentes"* e *"Desmarcar Todos"*.
- **Barra Flutuante Inferior Fixa (Mobile)**: Permite ao professor acompanhar a taxa de presença em tempo real e tocar em **`💾 Gravar Aula`** sem precisar rolar até o fim de listas longas de alunos.
- **Totalizadores em Tempo Real**: Contadores reativos de matriculados, presentes, ausentes, frequência (%) e visitantes.
- **Campos de Encerramento**: Registro de visitantes, bíblias, revistas e oferta em dinheiro arrecadada (R$).

### 3. Dashboard Consolidado em Tempo Real
- **Cabeçalho com Badges**: Exibição da congregação e contador dinâmico de status (*"X de Y Classes Entregues"*).
- **Seletor de Data de Referência**: Calendário com ícone vetorial interno e atualização reativa do painel.
- **Grid de 6 KPIs**:
  - *Alunos Presentes* (com comparativo de matriculados e % de presença).
  - *Visitantes* (não matriculados).
  - *Bíblias* (unidades trazidas à aula).
  - *Revistas* (lições estudadas).
  - *Total Geral da EBD* (destaque elegante com degradê azul royal somando alunos e visitantes).
  - *Total Ofertas* (destaque moderno em verde esmeralda com o valor total arrecadado).
- **Quadro de Classes com Visão Híbrida**:
  - **No Celular (`block md:hidden`)**: Cards táteis individuais com status pill (*Pendente* ou *Entregue*), resumo em 4 colunas (Matrícula, Presentes, Frequência %, Ofertas R$), sanfona expansível (*Accordion*) para detalhar visitantes/bíblias/revistas e botão largo de ação (48px).
  - **No Desktop (`hidden md:block`)**: Tabela gerencial de 11 colunas com cabeçalho slate sutil, linhas com hover suave e botões de ação compactos.

### 4. Barra de Navegação Desktop & Mobile (Navbar Híbrida)
- **Desktop**:
  - Efeito translúcido *glassmorphism* (`backdrop-blur-md bg-white/80 sticky top-0 z-40`).
  - Links com ícones vetoriais modernos de 18px (Dashboard, Chamadas, Classes, Alunos, Usuários, Auditoria).
  - Estado ativo em formato **pílula suave** (`bg-blue-50 text-blue-700 font-semibold px-3 py-2 rounded-xl`), sem sublinhados estáticos antigos.
  - Bloco de perfil à direita com badge de papel, avatar circular degradê com inicial, nome do usuário e menu dropdown.
- **Mobile**:
  - Topo compacto com logotipo e badge de perfil.
  - **Bottom Navigation Bar** fixa na base da tela (`[ 🏠 Painel | 📋 Chamadas | 👥 Alunos | 👤 Perfil ]`).
  - Gaveta de menu retrátil para acesso aos módulos administrativos.

### 5. Poder Administrativo: Redefinição Rápida de Senhas
- **Modal Instantâneo na Listagem (`/admin/usuarios`)**: O Administrador pode redefinir a senha de qualquer professor ou secretário com 1 toque, tanto no celular quanto no computador.
- **Gerador de Senhas Integrado**: O modal já sugere uma senha segura no formato `ebdXXXX`, com botão `🎲 Gerar` para sortear novas opções e botão para visualizar o texto digitado.
- **Efeito Imediato**: A senha é criptografada e o usuário já pode logar instantaneamente.

### 6. Trilha de Auditoria & Governança (Audit Logs)
- Registro imutável de todas as mutações relevantes do sistema na tabela `audit_logs` (criação, edição, chamadas, redefinição de senhas, ativação/desativação).
- Armazenamento estruturado de `user_id`, `ip_address`, `user_agent`, `payload_before` e `payload_after` em JSON.
- **Rotina de Limpeza Automática**: Comando Artisan `php artisan audit:prune --days=90` agendado para execução diária em `routes/console.php`.
- **Ação Manual do Administrador**: Botão de expurgo manual para descartar registros com mais de 30 dias.

---

## 🛠️ Tecnologias Utilizadas

- **Backend**: [Laravel 13.x](https://laravel.com/) (Framework PHP)
- **Linguagem**: PHP 8.2+ com tipagem estrita ativa (`<?php declare(strict_types=1);`)
- **Frontend Interativo**: [Livewire v4](https://livewire.laravel.com/) e [Alpine.js 3.x](https://alpinejs.dev/)
- **Estilização**: [Tailwind CSS 3.4+](https://tailwindcss.com/) com design system sob medida
- **Autenticação**: Laravel Breeze adaptado para múltiplos perfis com Blade e Tailwind
- **Tipografia**: Google Fonts (*Plus Jakarta Sans* e *Inter*)
- **Banco de Dados**: MySQL 8.0+ / MariaDB
- **Build Tool**: [Vite 7.x](https://vitejs.dev/)
- **Testes**: PHPUnit integrado ao Laravel

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
│   │   │   │   └── UserController.php     # Gestão de usuários e redefinição de senha
│   │   │   ├── AttendanceController.php   # Acesso ao módulo de chamada
│   │   │   ├── ProfileController.php      # Perfil do usuário conectado
│   │   │   └── Secretaria/
│   │   │       ├── ClassController.php    # Gestão de turmas e vinculação de professores
│   │   │       └── StudentController.php  # Cadastro de alunos
│   │   ├── Middleware/
│   │   │   └── CheckRole.php              # Proteção de rotas por perfis e bloqueio de inativos
│   │   └── Requests/                      # Validações estruturadas via Form Requests
│   ├── Livewire/
│   │   ├── DailyConsolidatedDashboard.php # Componente reativo do Dashboard Geral
│   │   └── TakeAttendance.php             # Componente reativo da Chamada Dominical
│   ├── Models/
│   │   ├── AuditLog.php                   # Model de auditoria
│   │   ├── EbdClass.php                   # Model de turmas (tabela `classes`)
│   │   ├── LessonAttendance.php           # Model de presença individual do aluno
│   │   ├── LessonRecord.php               # Model do cabeçalho da aula e fechamento
│   │   ├── Student.php                    # Model de alunos
│   │   └── User.php                       # Model de usuários com helpers de permissão
│   ├── Policies/
│   │   └── LessonRecordPolicy.php         # Regras de negócio de permissão de chamada
│   └── Services/
│       └── AuditService.php               # Serviço desacoplado de gravação de auditoria
├── database/
│   ├── migrations/                        # Estrutura relacional do banco de dados
│   └── seeders/DatabaseSeeder.php         # Povoamento inicial de usuários, turmas e alunos
├── lang/pt_BR/                            # Localização 100% em Português do Brasil
├── resources/
│   ├── css/app.css                        # Folha de estilos com Tailwind CSS
│   ├── js/app.js                          # Configuração de scripts e Alpine.js
│   └── views/                             # Templates Blade organizados por domínio
├── routes/
│   ├── console.php                        # Agendamento diário de expurgo de auditoria
│   └── web.php                            # Definição de rotas web e agrupamentos por perfil
└── tests/                                 # 35 testes automatizados cobrindo o fluxo completo
```

---

## 📋 Pré-requisitos para Execução

Antes de iniciar, certifique-se de possuir em seu ambiente:
- **PHP** >= 8.2 (com extensões `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `curl`, `gd`)
- **Composer** >= 2.x
- **Node.js** >= 18.x e **NPM**
- **MySQL** >= 8.0 ou **MariaDB** (ex: via XAMPP, WampServer ou Docker)

---

## ⚙️ Instalação Passo a Passo

### 1. Clonar o Repositório
```bash
git clone https://github.com/seu-usuario/ebdigital.git
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
Copie o arquivo `.env.example` para `.env` caso não exista:
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
Certifique-se de que o banco `ebdigital` existe no seu MySQL e execute:
```bash
php artisan migrate:fresh --seed
```

### 7. Compilar os Recursos do Frontend
Para desenvolvimento com Hot Module Replacement:
```bash
npm run dev
```

Ou para compilar a versão otimizada de produção:
```bash
npm run build
```

### 8. Iniciar o Servidor Local
Em outro terminal:
```bash
php artisan serve
```
O sistema estará disponível em: **`http://127.0.0.1:8000`**

---

## 🔐 Credenciais de Acesso para Testes

O seeder padrão (`DatabaseSeeder`) cria as seguintes contas demonstrativas para teste imediato de todos os perfis:

| Perfil | E-mail | Senha Padrão | Escopo de Demonstração |
| :--- | :--- | :--- | :--- |
| **Pastor / Administrador** | `admin@ebd.local` | `senha123` | Acesso a Usuários, Auditoria, Classes, Alunos e Dashboard Geral. |
| **Secretário / Superintendente** | `secretario@ebd.local` | `senha123` | Dashboard Consolidado, Retificações, Classes e Alunos. |
| **Professor 1 (Prof. Barnabé Silva)** | `professor1@ebd.local` | `senha123` | Chamada das Classes *Adultos (Bereanos)* e *Jovens*. |
| **Professor 2 (Profa. Débora Oliveira)** | `professor2@ebd.local` | `senha123` | Chamada das Classes *Adolescentes* e *Infantil*. |

> 💡 *Dica: A tela de login possui chips rápidos na parte inferior que preenchem as credenciais de qualquer perfil com apenas 1 clique.*

---

## 🧪 Testes Automatizados

A aplicação conta com uma suíte abrangente de **35 testes automatizados (96 asserções)** que garantem a estabilidade das regras de negócio, segurança e integridade das transações.

Para rodar todos os testes:
```bash
php artisan test
```

### Cobertura de Testes:
- **`AttendancePolicyTest`**: Garante que professores não consigam alterar chamadas de datas passadas nem turmas alheias, enquanto secretários possuem permissão de retificação retroativa.
- **`TakeAttendanceTransactionTest`**: Garante que o lançamento de chamada seja gravado dentro de `DB::transaction()` com integridade atômica e geração de logs de auditoria.
- **`AdminUserPasswordResetTest`**: Garante que apenas o Administrador possa redefinir senhas, rejeitando tentativas não autorizadas com `403 Forbidden` e gravando auditoria.
- **`AuditPruneTest`**: Valida a rotina programada diária de expurgo (90 dias) e a limpeza manual administrativa (30 dias).
- **Testes de Autenticação e Perfil**: Validação completa dos fluxos de login, recuperação de senha, bloqueio por força bruta e gerenciamento de conta.

---

## 🛡️ Diretrizes de Engenharia e Boas Práticas

- **Strict Types**: Todos os arquivos PHP do projeto utilizam `declare(strict_types=1);`.
- **Atomicidade Transacional**: Operações envolvendo múltiplas tabelas (como gravação de chamada em `lesson_records` e presenças em `lesson_attendances`) executam estritamente sob `DB::transaction()`.
- **Prevenção de Duplicidades**: Concorrência bloqueada em nível de banco através de índices compostos únicos (`class_id` + `lesson_date`).
- **Nomenclatura Semântica**: Models no singular PascalCase (ex: `EbdClass`), tabelas no plural snake_case (`classes`, `lesson_records`).
- **UI/UX Touch Target**: Alvos de toque touch nunca inferiores a 48px, garantindo conformidade com as diretrizes do Google Material e Apple HIG para smartphones.

---

## 📄 Licença

Este projeto é desenvolvido e mantido para a **Igreja Evangélica Assembleia de Deus**. Todos os direitos reservados.
