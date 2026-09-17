# Histórico de Versões e Atualizações - Caderneta EBD Digital

Documento oficial de versionamento e notas de lançamento (*Release Notes*) do ecossistema **Caderneta EBD Digital** (Assembleia de Deus).

Este projeto adota o padrão de Versionamento Semântico ([SemVer](https://semver.org/lang/pt-BR/)): `MAJOR.MINOR.PATCH`
- **MAJOR (Maior)**: Alterações arquiteturais profundas ou reformulações completas de módulos.
- **MINOR (Menor)**: Novas funcionalidades, novos módulos e grandes aprimoramentos de experiência.
- **PATCH (Correção)**: Correções de bugs, ajustes finos de layout, segurança e otimizações de performance.

---

## 🚀 Linha do Tempo de Versões

| Versão | Data de Lançamento | Principais Destaques |
| :--- | :---: | :--- |
| **v1.8.0** | 17/09/2026 | Central de Notificações, Aniversariantes & Cuidado Pastoral no Dashboard |
| **v1.7.0** | 16/09/2026 | Auto-Cadastro de Professores/Secretaria, Aprovação pelo Pastor e Integração WhatsApp |
| **v1.6.0** | 15/09/2026 | Controle de Versões, Modal "O que há de novo" no 1º login e `versoes.md` |
| **v1.5.0** | 12/09/2026 | Módulo Manual Didático Interativo, Busca em Tempo Real e Download do Manual em PDF |
| **v1.4.0** | 08/09/2026 | Autonomia de Professores, Matrícula Rápida em Sala e Relatórios por Turma |
| **v1.3.0** | 02/09/2026 | Sidebar Recolhível, `wire:navigate`, Política de Privacidade LGPD e TWA/Google Play |
| **v1.2.0** | 25/08/2026 | Arquitetura Multi-Tenant (Congregações), Tenant Switcher e Gestão de Professores |
| **v1.1.0** | 15/08/2026 | Módulo de Relatórios (Consolidado, Faltosos Crônicos, Aniversariantes e Impressão A4) |
| **v1.0.0** | 01/08/2026 | Lançamento Inicial de Produção (Chamada Mobile-First, Dashboard, Gestão e Auditoria) |

---

## Detalhamento das Versões

### [v1.8.0] - 17/09/2026
#### ✨ Novidades & Funcionalidades
- **Central de Lembretes & Cuidado Pastoral no Dashboard**: Painel interativo exibido no topo do `/dashboard` alertando o professor sobre os alunos aniversariantes e faltosos crônicos (3+ faltas seguidas) de suas turmas atribuídas.
- **Ações Rápidas de 1 Clique via WhatsApp (`wa.me`)**: Disparo de mensagens pré-formatadas cristãs e acolhedoras para o WhatsApp do aluno ou responsável sem custos e sem necessidade de integrações de terceiros.
- **Sino de Notificações Global**: Ícone de sino com badge contadora na navegação superior mobile, gaveta lateral e sidebar desktop direcionando para a seção de lembretes.
- **Isolamento de Turmas**: Professores visualizam estritamente os alunos de suas turmas vinculadas, enquanto secretários e administradores acompanham os alertas da congregação.

---

### [v1.7.0] - 16/09/2026
#### ✨ Novidades & Funcionalidades
- **Auto-Cadastro na Tela de Login**: Link e formulário dedicado permitindo que novos professores e secretários solicitem acesso diretamente pela interface pública, escolhendo sua congregação.
- **Fluxo de Aprovação Obrigatória**: Novos cadastros são registrados com retenção de segurança (`is_active = false`) aguardando liberação do Administrador ou Pastor da igreja antes de poderem entrar.
- **Integração Direta com WhatsApp**: Tela de confirmação contendo link e mensagem pré-formatada com os dados do usuário para contato direto via WhatsApp (+55 82 99630-4742) para agilização da aprovação.
- **Painel Administrativo com 1 Clique**: Banner de notificação na Gestão de Usuários indicando cadastros pendentes e botão de ação rápida "✓ Aprovar Acesso".

#### ⚡ Melhorias & Performance
- Criação de `RegisterRequest` com regras estritas de validação, bloqueio do papel de administrador em auto-cadastros e vinculação a congregações ativas.
- Mensagem amigável de bloqueio no login orientando usuários inativos a entrarem em contato pelo WhatsApp para liberação de acesso.

---

### [v1.6.0] - 15/09/2026
#### ✨ Novidades & Funcionalidades
- **Controle Automático de Versões**: O sistema agora monitora a versão visualizada por cada usuário através do campo `last_seen_version` na tabela `users`.
- **Modal "O Que Há de Novo" no Primeiro Login**: Ao acessar o sistema após uma nova atualização, um modal interativo é exibido automaticamente com os destaques e novidades da versão lançada.
- **Histórico Geral de Versões (`versoes.md`)**: Arquivo central na raiz do projeto documentando todo o histórico de lançamentos do software.
- **Acesso Permanente às Novidades**: Botão com indicador de versão adicionado na Sidebar Desktop e na Gaveta Mobile, permitindo aos usuários rever as notas de qualquer versão quando desejarem.

#### ⚡ Melhorias & Performance
- Estrutura de notas de versão centralizada em `config/changelog.php`, permitindo deploy ágil de comunicados versionados no repositório Git.
- Botões de confirmação de leitura com área de toque mínima de 48px, seguindo diretrizes de acessibilidade e touch mobile.

---

### [v1.5.0] - 12/09/2026
#### ✨ Novidades & Funcionalidades
- **Módulo Manual Didático Interativo (`/manual`)**: Guia completo de uso da Caderneta EBD acessível a todos os perfis (Pastores, Secretários e Professores).
- **Abas Segmentadas por Perfil**: Orientações especializadas para o dia a dia do Professor em sala de aula, rotinas da Secretaria/Superintendência e visão estratégica para o Pastor/Administrador Geral.
- **Busca em Tempo Real**: Mecanismo instantâneo para filtragem de tópicos, dúvidas frequentes e procedimentos operacionais.
- **Instalação como Aplicativo (PWA)**: Instruções ilustradas passo a passo para instalação no Android (Google Chrome) e iOS (Safari).
- **Download do Manual Oficial em PDF (`manual.pdf`)**: Documento oficial com timbre da igreja disponível para download direto e impressão.

#### ⚡ Melhorias & Performance
- Integração do link de Ajuda com badge de destaque nos menus de navegação desktop e mobile.

---

### [v1.4.0] - 08/09/2026
#### ✨ Novidades & Funcionalidades
- **Matrícula Rápida em Sala de Aula**: Professores podem cadastrar novos alunos diretamente dentro da tela de chamada dominical com inclusão imediata e presença marcada.
- **Gestão de Alunos Descentralizada**: Permissão estendida para professores cadastrarem, editarem e gerenciarem os alunos pertencentes exclusivamente às suas turmas atribuídas.
- **Modal de Confirmação de Chamada**: Diálogo com resumo dos indicadores (matriculados, presentes, visitantes, bíblias, revistas e ofertas) antes do envio final.
- **Relatórios da Turma para Professores**: Acesso seguro e escopado à central de relatórios (`/relatorios`), permitindo ao professor analisar o desempenho apenas de suas próprias classes.

#### ⚡ Melhorias & Performance
- Redirecionamento unificado pós-login para o Dashboard Central (`/dashboard`) para todos os perfis de usuários.
- Isolamento rigoroso via `StudentPolicy` e `LessonRecordPolicy`, impedindo manipulações indevidas de turmas externas.

---

### [v1.3.0] - 02/09/2026
#### ✨ Novidades & Funcionalidades
- **Sidebar com Modo Recolhível (Collapse)**: Alternância suave entre largura completa e modo compacto com ícones, ideal para tablets e telas verticais.
- **Persistência de Preferência de Interface**: O estado recolhido/expandido é lembrado automaticamente via `localStorage`.
- **Navegação SPA Ultra-Rápida (`wire:navigate`)**: Transições de tela sem recarregamento completo e barra de progresso superior translúcida com animação suave.
- **Conformidade Google Play & TWA**: Configuração de `.well-known/assetlinks.json` para validação de domínio em pacotes Android.
- **Página Pública de Política de Privacidade (`/politica-de-privacidade`)**: Adequação integral à LGPD e diretrizes de publicação de aplicativos móveis.
- **Identidade & Créditos**: Link oficial da desenvolvedora KL Tecnologia no rodapé de autenticação.

---

### [v1.2.0] - 25/08/2026
#### ✨ Novidades & Funcionalidades
- **Arquitetura Multi-Tenant Completa**: Criação da entidade Congregações (`congregations`), permitindo que a EBD seja utilizada em múltiplas congregações do campo eclesiástico.
- **Isolamento de Dados em Nível de Banco**: Aplicação automática do `CongregationScope` e Trait `BelongsToCongregation` em Classes, Alunos, Chamadas, Usuários e Logs.
- **Alternador de Congregação (Tenant Switcher)**: Menu suspenso para Administradores navegarem entre "Todas as Congregações" ou focarem em uma unidade específica em tempo real.
- **Módulo de Gestão de Professores**: Tela administrativa para a Secretaria da Congregação cadastrar, editar, inativar e redefinir senhas dos professores locais.

#### 🛡️ Segurança & Regras de Negócio
- Bloqueio preventivo de mutações (chamadas e cadastros) quando o contexto global estiver em "Todas as Congregações", garantindo integridade cadastral.

---

### [v1.1.0] - 15/08/2026
#### ✨ Novidades & Funcionalidades
- **Central de Relatórios da EBD (`/relatorios`)**: Módulo analítico com filtros dinâmicos por mês, trimestre, ano ou período customizado.
- **Relatório Consolidado Trimestral**: Comparativo estatístico de frequência, matrículas, visitantes, revistas, bíblias e arrecadação de ofertas por classe.
- **Relatório de Frequência Nominal & Alerta Pastoral**: Listagem de assiduidade dos alunos com destaque visual em vermelho para faltosos crônicos (3 ou mais faltas consecutivas) para visitação.
- **Relatório de Aniversariantes**: Listagem por data de nascimento com cálculo de idade e botão direto para felicitação via WhatsApp com mensagem personalizada.
- **Layout Oficial de Impressão A4**: Estilização gráfica para papel com cabeçalho oficial da Assembleia de Deus, dados de contato e campos para assinaturas do Pastor e do Superintendente da EBD.

---

### [v1.0.0] - 01/08/2026
#### ✨ Lançamento Inicial de Produção
- **Autenticação & Controle de Acesso**: Suporte aos perfis Administrador Geral, Secretário / Superintendente e Professor da Classe.
- **Lançamento de Chamada Mobile-First**: Interface otimizada para smartphones com botões grandes de toque (48px+) para presença/falta de alunos e professores.
- **Métricas da Aula**: Registro integrado de visitantes, bíblias, revistas da CPAD e ofertas em dinheiro com transações atômicas seguras (`DB::transaction`).
- **Dashboard Diário Consolidado**: Resumo em tempo real dos números gerais do domingo da EBD.
- **Gestão Escolar**: CRUD completo de Classes/Turmas, Alunos matriculados e Usuários do sistema.
- **Auditoria de Ações (`AuditLog`)**: Rastreabilidade de logins, alterações em chamadas e cadastros, com comando automatizado de expurgo periódico (`audit:prune`).

---

*Caderneta EBD Digital — Desenvolvido com excelência para a Igreja Evangélica Assembleia de Deus.*
