# Guia de Deploy

Este guia fornece instruções para fazer deploy do Facebug em um servidor de produção usando Docker.

## Pré-requisitos

- Docker
- Docker Compose
- Git

## Instalação

1. Clone o repositório:
```bash
git clone https://github.com/elian-sss/facebug.git
cd facebug
```

2. Configure o ambiente:
```bash
cp .env.example .env
```

3. Configure as variáveis de ambiente no arquivo `.env`:
```env
APP_ENV=production
APP_DEBUG=false

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=facebug
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

4. Inicie os containers Docker:
```bash
docker-compose up -d
```

5. Instale as dependências e configure o projeto:
```bash
docker-compose exec app composer install --no-dev --optimize-autoloader
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
docker-compose exec app npm install
docker-compose exec app npm run build
```

   Após a execução do seed, os seguintes dados de exemplo estarão disponíveis:

   - Usuário administrador:
     - Email: elian@example.com
     - Senha: password
   
   - Usuários de exemplo:
     - maria@exemplo.com
     - joao@exemplo.com
     - ana@exemplo.com
     - pedro@exemplo.com
     - (+ 6 outros usuários)
     - Senha padrão para todos: password
   
   - Conteúdo gerado automaticamente:
     - 3 posts por usuário
     - 1-5 comentários por post
     - 1-5 curtidas por post

6. Configure as permissões:
```bash
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

7. Otimize a aplicação:
```bash
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

## Estrutura Docker

O ambiente Docker inclui os seguintes serviços:

- **app**: PHP-FPM 8.1 com as extensões necessárias
- **nginx**: Servidor web Nginx
- **mysql**: Banco de dados MySQL 8.0
- **redis**: Redis para cache e sessões

## Manutenção

### Atualização da Aplicação

Para atualizar a aplicação:

```bash
# Puxe as últimas alterações
git pull origin main

# Atualize as dependências
docker-compose exec app composer install --no-dev --optimize-autoloader
docker-compose exec app npm install
docker-compose exec app npm run build

# Atualize o banco de dados
docker-compose exec app php artisan migrate

# Limpe os caches
docker-compose exec app php artisan optimize:clear
docker-compose exec app php artisan optimize
```

### Logs

Para visualizar os logs:

```bash
# Logs de todos os serviços
docker-compose logs

# Logs de um serviço específico
docker-compose logs app
docker-compose logs nginx
```

### Backup do Banco de Dados

Para fazer backup do banco de dados:

```bash
docker-compose exec mysql mysqldump -u root -p facebug > backup.sql
```

## Considerações de Segurança

1. Sempre altere as senhas padrão nos arquivos de configuração
2. Mantenha o Docker e as imagens atualizadas
3. Configure SSL/TLS para HTTPS
4. Configure um firewall
5. Faça backups regulares

## Monitoramento

Recomenda-se configurar monitoramento para:

- Uso de CPU e memória
- Espaço em disco
- Status dos containers
- Logs de erro
- Tempo de resposta da aplicação