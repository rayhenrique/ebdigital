<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Versão Atual do Sistema
    |--------------------------------------------------------------------------
    |
    | Define a versão ativa da Caderneta EBD Digital. Quando este valor for
    | incrementado (ex: 1.6.0 para 1.7.0), os usuários verão o modal de
    | novidades automaticamente em seu próximo login ou carregamento de página.
    |
    */
    'current_version' => '1.8.0',

    /*
    |--------------------------------------------------------------------------
    | Histórico de Notas de Lançamento (Release Notes)
    |--------------------------------------------------------------------------
    |
    | Cada versão contém data, título, descrição geral e lista de destaques
    | classificados por tipo ('feature', 'improvement', 'fix').
    |
    */
    'releases' => [
        '1.8.0' => [
            'version' => '1.8.0',
            'date' => '17/09/2026',
            'title' => 'Central de Notificações, Aniversariantes & Cuidado Pastoral',
            'badge' => 'Versão Atual',
            'description' => 'Lembretes automáticos no Dashboard alertando professores sobre aniversariantes da semana e alunos com 3+ faltas seguidas, com botões de 1 clique para WhatsApp.',
            'highlights' => [
                [
                    'type' => 'feature',
                    'title' => 'Lembretes Automáticos no Dashboard',
                    'description' => 'Painel destacado no topo da tela inicial com os aniversariantes e alunos faltosos da turma, sem precisar vasculhar relatórios manualmente.',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Ações Rápidas via WhatsApp (wa.me)',
                    'description' => 'Botões de 1 clique com mensagens pré-formatadas cristãs e acolhedoras para envio direto pelo WhatsApp do próprio professor.',
                ],
                [
                    'type' => 'improvement',
                    'title' => 'Sino de Notificações Global',
                    'description' => 'Contador dinâmico de pendências no topo do aplicativo e menu lateral com acesso direto aos lembretes.',
                ],
            ],
        ],
        '1.7.0' => [
            'version' => '1.7.0',
            'date' => '16/09/2026',
            'title' => 'Auto-Cadastro com Aprovação & Integração WhatsApp',
            'badge' => 'Versão Anterior',
            'description' => 'Novo fluxo de solicitação de cadastro diretamente na tela de login, com aprovação prévia pelo Administrador/Pastor e agilização via WhatsApp.',
            'highlights' => [
                [
                    'type' => 'feature',
                    'title' => 'Auto-Cadastro na Tela de Login',
                    'description' => 'Professores e secretários podem solicitar acesso selecionando sua respectiva congregação.',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Integração Direta com WhatsApp',
                    'description' => 'Link oficial com mensagem formatada e dados do usuário para solicitar liberação imediata ao Pastor/Admin.',
                ],
                [
                    'type' => 'improvement',
                    'title' => 'Painel de Aprovação Rápida',
                    'description' => 'Alerta visual de cadastros pendentes na gestão de usuários e aprovação em 1 clique.',
                ],
            ],
        ],

        '1.6.0' => [
            'version' => '1.6.0',
            'date' => '15/09/2026',
            'title' => 'Controle de Versões & Central de Novidades',
            'badge' => 'Novidades',
            'description' => 'Apresentamos a Central de Novidades da Caderneta EBD! A partir de agora, sempre que novas funções forem publicadas, você será avisado imediatamente em seu primeiro login.',
            'highlights' => [
                [
                    'type' => 'feature',
                    'title' => 'Aviso Automático no Primeiro Login',
                    'description' => 'Você sempre ficará por dentro das melhorias assim que acessar sua conta, sem perder nenhuma novidade.',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Histórico Geral de Versões',
                    'description' => 'Registro oficial no arquivo versoes.md catalogando todas as fases do projeto desde o lançamento inicial.',
                ],
                [
                    'type' => 'improvement',
                    'title' => 'Acesso Permanente no Menu',
                    'description' => 'Consulte o histórico de atualizações a qualquer momento clicando em "Novidades" na barra lateral ou na gaveta mobile.',
                ],
            ],
        ],

        '1.5.0' => [
            'version' => '1.5.0',
            'date' => '12/09/2026',
            'title' => 'Manual Didático Interativo & Suporte PWA',
            'badge' => 'Ajuda & Guia',
            'description' => 'Lançamento do módulo de Manual de Instruções com passo a passo para todos os membros da equipe da Escola Bíblica Dominical.',
            'highlights' => [
                [
                    'type' => 'feature',
                    'title' => 'Manual Interativo em Abas',
                    'description' => 'Orientações didáticas organizadas por perfil: Professor, Secretário/Superintendente e Pastor/Administrador.',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Busca Rápida de Dúvidas',
                    'description' => 'Filtro em tempo real para encontrar procedimentos sobre chamadas, ofertas, matrículas e relatórios.',
                ],
                [
                    'type' => 'improvement',
                    'title' => 'Download do Manual em PDF',
                    'description' => 'Geração do manual institucional pronto para impressão ou consulta offline.',
                ],
            ],
        ],

        '1.4.0' => [
            'version' => '1.4.0',
            'date' => '08/09/2026',
            'title' => 'Autonomia dos Professores & Matrícula Rápida',
            'badge' => 'Sala de Aula',
            'description' => 'Melhorias significativas para agilizar a rotina dos professores durante o momento da aula.',
            'highlights' => [
                [
                    'type' => 'feature',
                    'title' => 'Matrícula Rápida na Chamada',
                    'description' => 'Cadastre novos alunos instantaneamente durante a chamada com presença já computada no dia.',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Gestão de Alunos da Turma',
                    'description' => 'Professores agora podem gerenciar os dados dos alunos pertencentes às suas próprias classes.',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Relatórios da Turma',
                    'description' => 'Acesso exclusivo para professores aos relatórios e histórico de presença de suas classes.',
                ],
                [
                    'type' => 'improvement',
                    'title' => 'Confirmação Segura de Chamada',
                    'description' => 'Modal com conferência dos números antes da gravação definitiva da aula.',
                ],
            ],
        ],

        '1.3.0' => [
            'version' => '1.3.0',
            'date' => '02/09/2026',
            'title' => 'Sidebar Recolhível & Conformidade Google Play',
            'badge' => 'Design & Mobile',
            'description' => 'Interface moderna com melhor aproveitamento do espaço em tablets e smartphones.',
            'highlights' => [
                [
                    'type' => 'feature',
                    'title' => 'Sidebar Desktop com Modo Recolhível',
                    'description' => 'Recolha a barra lateral para ganhar mais visibilidade na tela, com memória no navegador.',
                ],
                [
                    'type' => 'improvement',
                    'title' => 'Navegação Ultra-Rápida',
                    'description' => 'Troca instantânea de telas sem piscar com carregamento otimizado.',
                ],
                [
                    'type' => 'fix',
                    'title' => 'Política de Privacidade LGPD',
                    'description' => 'Página oficial de privacidade em conformidade com as diretrizes da Google Play Store.',
                ],
            ],
        ],

        '1.2.0' => [
            'version' => '1.2.0',
            'date' => '25/08/2026',
            'title' => 'Suporte a Múltiplas Congregações (Multi-Tenant)',
            'badge' => 'Eclesiástico',
            'description' => 'A Caderneta EBD agora suporta todo o campo eclesiástico com segregação por congregação.',
            'highlights' => [
                [
                    'type' => 'feature',
                    'title' => 'Módulo de Congregações',
                    'description' => 'Cadastro de congregações e isolamento completo de classes, alunos e chamadas.',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Alternador de Congregação Ativa',
                    'description' => 'Administradores podem alternar facilmente entre congregações no menu superior.',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Gestão Local de Professores',
                    'description' => 'Secretaria da congregação gerencia e redefine senhas dos seus próprios professores.',
                ],
            ],
        ],

        '1.1.0' => [
            'version' => '1.1.0',
            'date' => '15/08/2026',
            'title' => 'Central de Relatórios Oficiais da EBD',
            'badge' => 'Estatísticas',
            'description' => 'Módulo completo de relatórios trimestrais e acompanhamento pastoral de alunos.',
            'highlights' => [
                [
                    'type' => 'feature',
                    'title' => 'Relatório Trimestral e Mensal',
                    'description' => 'Comparativo completo de matrículas, frequência, revistas e ofertas por classe.',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Alerta de Faltosos Crônicos',
                    'description' => 'Identificação imediata de alunos com 3+ faltas consecutivas para visitação pastoral.',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Aniversariantes & Parabéns no WhatsApp',
                    'description' => 'Envio de felicitação aos aniversariantes do mês direto pelo WhatsApp.',
                ],
                [
                    'type' => 'improvement',
                    'title' => 'Impressão Oficial em A4',
                    'description' => 'Folha de relatório formatada para papel com assinaturas do Pastor e Superintendente.',
                ],
            ],
        ],

        '1.0.0' => [
            'version' => '1.0.0',
            'date' => '01/08/2026',
            'title' => 'Lançamento Oficial da Caderneta EBD Digital',
            'badge' => 'Lançamento',
            'description' => 'Entrada em produção da plataforma digital de gestão e chamada dominical.',
            'highlights' => [
                [
                    'type' => 'feature',
                    'title' => 'Chamada Mobile-First',
                    'description' => 'Interface rápida com botões grandes de toque para presença e falta de alunos.',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Métricas da Aula',
                    'description' => 'Contagem de bíblias, revistas, visitantes e ofertas em dinheiro com transações seguras.',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Dashboard Consolidado',
                    'description' => 'Visão geral em tempo real dos números do domingo na EBD.',
                ],
                [
                    'type' => 'feature',
                    'title' => 'Auditoria Completa',
                    'description' => 'Rastreamento de todas as alterações com segurança e expurgo automático.',
                ],
            ],
        ],
    ],
];
