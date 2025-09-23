#!/bin/bash

# Espera o MySQL estar pronto
echo "Aguardando o MySQL..."
while ! mysqladmin ping -h"$DB_HOST" --silent; do
    sleep 1
done

echo "🚀 Iniciando configuração do Facebug..."

# Instala dependências do Composer se necessário
if [ ! -d "vendor" ]; then
    echo "📦 Instalando dependências do Composer..."
    composer install --no-dev --optimize-autoloader
fi

# Gera chave se necessário
if [ ! -f ".env" ]; then
    echo "🔑 Configurando ambiente..."
    cp .env.example .env
    php artisan key:generate
fi

# Executa migrações e seeds
echo "🔄 Configurando banco de dados..."
php artisan migrate:fresh --seed --force

# Otimiza a aplicação
echo "⚡ Otimizando a aplicação..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✨ Configuração concluída!"
echo "
Dados de acesso:
📧 Admin: elian@example.com
🔐 Senha: password

Outros usuários:
👤 maria@exemplo.com
👤 joao@exemplo.com
👤 ana@exemplo.com
👤 pedro@exemplo.com
🔐 Senha padrão: password
"

# Inicia o PHP-FPM
php-fpm