# Definição de Escopo do MVP - Caderneta EBD

## 1. Escopo Aprovado (Core Features)
* **Chamada Mobile-First (Livewire):** Interface limpa para uso em sala de aula via smartphone, com totalizadores de rodapé.
* **Flexibilidade de Calendário:** Capacidade de registrar chamadas em qualquer dia da semana (não travado em domingos), útil para aulas de sábado, feriados ou remanejamentos.
* **Segurança e Governança:**
  * Professor edita apenas chamadas da data corrente.
  * Secretário tem poder de retificação retroativa (salvando logs).
* **Consolidação em Tempo Real:** Dashboard unificado gerando totais financeiros e de frequência instantâneos para a secretaria.
* **Auditoria Enxuta:** Gravação de ações críticas com expurgo automático (90 dias) e manual (30 dias).

## 2. Escopo Explicitamente Postergado (Fora do MVP)
* Arquitetura Multi-tenant (SaaS para várias igrejas).
* Controle financeiro de dízimos/ofertas nominais por aluno (anonimização mantida).
* Integração com gateways de pagamento ou ERP contábil.
* Exportação de PDF complexo reproduzindo visualmente a grade da caderneta de papel (foco em UI web limpa).
* Aplicativo nativo em App Store / Google Play (será PWA).

## 3. Critérios de Sucesso do MVP
1. Zero concorrência destrutiva: Duas tentativas de salvar a mesma classe na mesma data devem ser tratadas corretamente pelo banco.
2. Usabilidade: O preenchimento da frequência e consolidação não deve ultrapassar 2 minutos por classe via mobile.
3. Precisão: O somatório financeiro e de presenças do dashboard do secretário deve bater 100% com as chamadas das classes do dia.