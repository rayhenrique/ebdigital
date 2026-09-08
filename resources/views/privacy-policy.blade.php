<!DOCTYPE html>
<html lang="pt-BR" class="notranslate" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-language" content="pt-BR">
    <meta name="google" content="notranslate">
    <meta name="description" content="Política de Privacidade oficial do aplicativo Caderneta EBD Online - Igreja Evangélica Assembleia de Deus em Teotônio Vilela / AL. Desenvolvido por Ray Henrique.">
    <meta name="author" content="Ray Henrique">

    <title>Política de Privacidade — Caderneta EBD Online</title>

    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Favicon & PWA -->
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <meta name="theme-color" content="#2563eb">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-800 selection:bg-blue-600 selection:text-white min-h-screen py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Top Bar / Header com Logotipo e Voltar -->
        <div class="flex items-center justify-between pb-6 border-b border-slate-200/80 mb-8">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-3 group">
                <img 
                    src="{{ asset('images/logo-ad-transparent.png') }}" 
                    alt="Logotipo Assembleia de Deus" 
                    class="h-10 w-auto object-contain transition-transform duration-200 group-hover:scale-105"
                />
                <div class="flex flex-col">
                    <span class="font-extrabold text-base sm:text-lg text-slate-850 tracking-tight leading-tight">
                        Caderneta <span class="text-blue-600">EBD Online</span>
                    </span>
                    <span class="text-xs text-slate-400 font-medium leading-none">
                        Assembleia de Deus — Teotônio Vilela / AL
                    </span>
                </div>
            </a>

            <a 
                href="{{ url('/') }}" 
                class="inline-flex items-center gap-1.5 px-4 py-2 text-xs sm:text-sm font-semibold text-slate-600 hover:text-blue-600 bg-white hover:bg-blue-50 border border-slate-200 rounded-xl transition shadow-2xs"
            >
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                <span>Voltar ao Sistema</span>
            </a>
        </div>

        <!-- Conteúdo Principal do Documento -->
        <main class="bg-white rounded-3xl shadow-sm border border-slate-200/80 p-6 sm:p-10 space-y-8">
            <!-- Título Principal -->
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100 uppercase tracking-wider mb-3">
                    Documento Oficial de Conformidade
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Política de Privacidade
                </h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-1">
                    Última atualização: <strong class="text-slate-600">Setembro de 2026</strong>
                </p>
            </div>

            <!-- Card de Identificação Exata da Aplicação e Desenvolvedor (Google Play Requirement) -->
            <div class="rounded-2xl bg-gradient-to-br from-slate-50 to-blue-50/40 border border-blue-100 p-5 sm:p-6">
                <h2 class="text-xs font-extrabold text-blue-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                    </svg>
                    Identificação da Aplicação e Desenvolvedor
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs sm:text-sm">
                    <div class="space-y-1">
                        <span class="text-slate-400 font-medium block">Nome do Aplicativo:</span>
                        <strong class="text-slate-800 text-sm font-bold block">Caderneta EBD Online</strong>
                    </div>
                    <div class="space-y-1">
                        <span class="text-slate-400 font-medium block">Identificador do Pacote (Package ID):</span>
                        <code class="px-2 py-0.5 rounded-md bg-white border border-slate-200 text-blue-600 font-mono text-xs font-bold inline-block">
                            br.com.adteotoniovilela.cadernetaebd
                        </code>
                    </div>
                    <div class="space-y-1">
                        <span class="text-slate-400 font-medium block">Desenvolvedor Responsável:</span>
                        <strong class="text-slate-800 text-sm font-bold block">Ray Henrique</strong>
                    </div>
                    <div class="space-y-1">
                        <span class="text-slate-400 font-medium block">Entidade Eclesiástica Atendida:</span>
                        <strong class="text-slate-800 text-sm font-bold block">Igreja Evangélica Assembleia de Deus em Teotônio Vilela / AL</strong>
                    </div>
                    <div class="space-y-1">
                        <span class="text-slate-400 font-medium block">E-mail do Desenvolvedor:</span>
                        <a href="mailto:rayhenrique@gmail.com" class="text-blue-600 hover:underline font-semibold block">rayhenrique@gmail.com</a>
                    </div>
                    <div class="space-y-1">
                        <span class="text-slate-400 font-medium block">Contato Oficial da Igreja:</span>
                        <a href="mailto:contato@adteotoniovilela.com.br" class="text-blue-600 hover:underline font-semibold block">contato@adteotoniovilela.com.br</a>
                    </div>
                </div>
            </div>

            <!-- Seções da Política -->
            <div class="space-y-6 text-sm text-slate-700 leading-relaxed">
                <!-- Seção 1 -->
                <section class="space-y-2">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-extrabold">1</span>
                        Introdução e Propósito
                    </h2>
                    <p>
                        Esta Política de Privacidade descreve como o aplicativo <strong>Caderneta EBD Online</strong> (identificador <code class="text-xs font-mono bg-slate-100 px-1 py-0.5 rounded">br.com.adteotoniovilela.cadernetaebd</code>), desenvolvido por <strong>Ray Henrique</strong> para uso eclesiástico da <strong>Igreja Evangélica Assembleia de Deus em Teotônio Vilela / AL</strong>, coleta, utiliza, armazena e protege os dados de seus usuários (administradores, secretários e professores da Escola Bíblica Dominical).
                    </p>
                </section>

                <!-- Seção 2 -->
                <section class="space-y-2">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-extrabold">2</span>
                        Dados Coletados
                    </h2>
                    <p>
                        Para viabilizar a operação da caderneta digital e o gerenciamento pedagógico, coletamos estritamente:
                    </p>
                    <ul class="list-disc list-inside space-y-1 pl-2 text-slate-700">
                        <li>
                            <strong>Dados de Autenticação dos Usuários (Professores e Liderança)</strong>: Nome completo, endereço de e-mail institucional/pessoal e senha criptografada.
                        </li>
                        <li>
                            <strong>Registros Eclesiásticos e Pedagógicos da EBD</strong>: Nome dos alunos matriculados, data de nascimento, registro de frequência às aulas dominicais, apontamento de visitantes, número de bíblias e revistas trazidas, além de valores consolidados de ofertas arrecadadas por classe.
                        </li>
                    </ul>
                </section>

                <!-- Seção 3 -->
                <section class="space-y-2">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-extrabold">3</span>
                        Finalidade do Tratamento de Dados
                    </h2>
                    <p>
                        Os dados tratados pela aplicação destinam-se exclusivamente a:
                    </p>
                    <ul class="list-disc list-inside space-y-1 pl-2 text-slate-700">
                        <li>Permitir o login seguro e controle de permissões no sistema (Pastor, Secretário e Professor).</li>
                        <li>Substituição de cadernetas físicas em papel pelo registro eletrônico de presenças.</li>
                        <li>Geração de relatórios de frequência, acompanhamento de evasão de alunos e fechamento estatístico dominical para a secretaria da igreja.</li>
                    </ul>
                    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-900 font-semibold text-xs sm:text-sm mt-2">
                        🛡️ <strong>Compromisso de Privacidade:</strong> Não comercializamos, não compartilhamos com redes de anúncios e não cedemos nenhum dado a terceiros.
                    </div>
                </section>

                <!-- Seção 4 -->
                <section class="space-y-2">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-extrabold">4</span>
                        Armazenamento e Segurança da Informação
                    </h2>
                    <p>
                        Todas as informações são transmitidas através de tráfego seguro criptografado com tecnologia HTTPS/SSL. Os dados ficam armazenados em servidores seguros, com senhas salvas através de algoritmos modernos de dispersão irreversível (hash bcrypt/argon2), com rotinas periódicas de backup e proteção contra acessos não autorizados.
                    </p>
                </section>

                <!-- Seção 5 -->
                <section class="space-y-2">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-extrabold">5</span>
                        Privacidade de Menores de Idade
                    </h2>
                    <p>
                        O aplicativo registra nomes de alunos de classes infantis e infanto-juvenis exclusivamente como lista de presença escolar dominical, cadastrados e monitorados pela secretaria da igreja ou seus responsáveis legais. O aplicativo não coleta dados de geolocalização, contatos ou dados sensíveis de menores, nem permite que menores criem contas de acesso público independentes.
                    </p>
                </section>

                <!-- Seção 6 -->
                <section class="space-y-2">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-extrabold">6</span>
                        Exclusão de Conta e Direitos do Titular (LGPD)
                    </h2>
                    <p>
                        Em total conformidade com a Lei Geral de Proteção de Dados (LGPD) e as diretrizes da Google Play Store:
                    </p>
                    <p>
                        Qualquer usuário ou membro pode solicitar a retificação, exportação ou exclusão definitiva de seus dados e conta do sistema. Para solicitar a exclusão de sua conta e registros vinculados, basta encaminhar um e-mail com a solicitação para <a href="mailto:contato@adteotoniovilela.com.br" class="text-blue-600 underline font-semibold">contato@adteotoniovilela.com.br</a> ou solicitar diretamente à secretaria da igreja local. As informações serão anonimizadas ou permanentemente excluídas dos nossos servidores em até 15 dias úteis.
                    </p>
                </section>

                <!-- Seção 7 -->
                <section class="space-y-2">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-extrabold">7</span>
                        Alterações a Esta Política
                    </h2>
                    <p>
                        Reservamo-nos o direito de atualizar este documento periodicamente para refletir melhorias técnicas e novos recursos na aplicação. Qualquer alteração relevante será indicada pela data de atualização no topo desta página.
                    </p>
                </section>

                <!-- Seção 8 -->
                <section class="space-y-2">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-extrabold">8</span>
                        Canal de Contato
                    </h2>
                    <p>
                        Dúvidas sobre a privacidade e o tratamento dos dados da aplicação <strong>Caderneta EBD Online</strong> podem ser dirigidas ao responsável:
                    </p>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm space-y-1">
                        <p><strong>Desenvolvedor:</strong> Ray Henrique</p>
                        <p><strong>Instituição:</strong> Igreja Evangélica Assembleia de Deus – Teotônio Vilela / AL</p>
                        <p><strong>E-mail de Contato:</strong> <a href="mailto:rayhenrique@gmail.com" class="text-blue-600 hover:underline">rayhenrique@gmail.com</a></p>
                        <p><strong>E-mail Institucional:</strong> <a href="mailto:contato@adteotoniovilela.com.br" class="text-blue-600 hover:underline">contato@adteotoniovilela.com.br</a></p>
                    </div>
                </section>
            </div>
        </main>

        <!-- Rodapé -->
        <footer class="mt-8 text-center text-xs text-slate-400 space-y-1">
            <p>© {{ date('Y') }} Igreja Evangélica Assembleia de Deus em Teotônio Vilela / AL. Todos os direitos reservados.</p>
            <p>Caderneta EBD Online — Desenvolvido por Ray Henrique</p>
        </footer>
    </div>
</body>
</html>
