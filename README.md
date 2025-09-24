# Facebug

Uma aplicação de mídia social construída com Laravel, Inertia.js e TypeScript.

## Requisitos

- PHP 8.2 ou superior
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

### Dados de Exemplo

Após executar o seeder, os seguintes dados estarão disponíveis para teste:

#### Usuários
- **Administrador**
  - Email: elian@example.com
  - Senha: password

- **Usuários de Teste**
  - maria@exemplo.com
  - joao@exemplo.com
  - ana@exemplo.com
  - pedro@exemplo.com
  - (e mais 6 usuários gerados automaticamente)
  - Senha padrão para todos: password

#### Conteúdo
- 3 posts por usuário
- 1-5 comentários aleatórios por post
- 1-5 curtidas aleatórias por post

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

## Solução de Problemas

### Erro "zip extension is missing" no Composer

Se você encontrar um erro durante o `composer install` informando que a extensão `zip` está faltando, siga estes passos:

1.  **Localize seu arquivo `php.ini`**. Em ambientes como o XAMPP, ele geralmente está em `C:\xampp\php\php.ini`.
2.  Abra o arquivo em um editor de texto.
3.  Procure pela linha `;extension=zip`.
4.  Remova o ponto e vírgula (`;`) do início da linha para descomentá-la. A linha deve ficar assim:
    ```ini
    extension=zip
    ```
5.  Salve o arquivo.
6.  **Reinicie seu terminal e seu servidor web (Apache, no caso do XAMPP)** para que a alteração tenha efeito.
7.  Tente rodar `composer install` novamente.

## Licença

[MIT License](https://www.google.com/search?q=LICENSE)

## Licença

[MIT License](LICENSE)
