# Facebug

Uma aplicação de mídia social construída com Laravel, Inertia.js e TypeScript.

## Requisitos

- PHP 8.1 ou superior
- Node.js 16+ e npm
- MySQL 5.7 ou superior
- Composer

## Funcionalidades

- Autenticação e autorização de usuários
- Criação e gerenciamento de posts
- Comentários em posts
- Funcionalidade de curtir/descurtir
- Design responsivo

## Instalação

1. Clone o repositório:
```bash
git clone https://github.com/elian-sss/facebug.git
cd facebug
```

2. Instale as dependências PHP:
```bash
composer install
```

3. Instale as dependências JavaScript:
```bash
npm install
```

4. Configure o ambiente:
```bash
cp .env.example .env
php artisan key:generate
```

5. Configure o banco de dados:
   - Atualize as credenciais do banco no arquivo `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=facebug
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

6. Execute as migrações e seeds:
```bash
php database/setup.php
```

## Desenvolvimento

1. Inicie o servidor de desenvolvimento Laravel:
```bash
php artisan serve
```

2. Inicie o servidor de desenvolvimento Vite:
```bash
npm run dev
```

## Estrutura do Banco de Dados

A aplicação utiliza as seguintes tabelas principais:

- `users` - Armazena informações dos usuários
- `posts` - Armazena as postagens dos usuários
- `comments` - Armazena os comentários das postagens
- `likes` - Armazena as curtidas das postagens

## Licença

[MIT License](LICENSE)
