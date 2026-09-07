#!/usr/bin/env bash
# ==============================================================================
# Script de Atualização Rápida em Produção (Deploy Contínuo)
# Caderneta EBD Online - Assembleia de Deus em Teotônio Vilela
# ==============================================================================

set -e

echo "---------------------------------------------------------"
echo "🚀 Iniciando atualização da Caderneta EBD em Produção..."
echo "---------------------------------------------------------"

# 1. Ativar modo de manutenção temporário durante a atualização
echo "🔒 Ativando modo de manutenção..."
php artisan down --retry=60 || true

# 2. Puxar as últimas alterações do repositório
echo "📥 Puxando código mais recente do GitHub..."
git pull origin main

# 3. Instalar dependências PHP otimizadas
echo "🐘 Instalando dependências do Composer..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# 4. Compilar assets do Vite / Tailwind CSS
if command -v npm &> /dev/null
then
    echo "⚡ Instalando e compilando assets do Vite..."
    npm install --no-audit --no-fund
    npm run build
else
    echo "⚠️ Node/NPM não encontrado no ambiente do usuário. Pulando npm run build."
fi

# 5. Publicar assets estáticos do Livewire para Nginx
echo "📦 Publicando assets estáticos do Livewire..."
php artisan livewire:publish --assets || true

# 6. Executar migrações pendentes no banco de dados
echo "🗄️ Executando migrações no banco de dados..."
php artisan migrate --force

# 6. Limpar e recriar os caches de produção do Laravel
echo "⚡ Otimizando caches da aplicação..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Garantir permissões corretas nas pastas graváveis
echo "🔐 Ajustando permissões de storage e cache..."
chmod -R 775 storage bootstrap/cache

# 8. Desativar modo de manutenção
echo "🔓 Desativando modo de manutenção..."
php artisan up

echo "---------------------------------------------------------"
echo "✅ Deploy finalizado com sucesso! Aplicação 100% online."
echo "🔗 https://ebd.adteotoniovilela.com.br"
echo "---------------------------------------------------------"
