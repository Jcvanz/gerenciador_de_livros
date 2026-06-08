# Gerenciador de Empréstimo de Livros

Este projeto é um sistema de gerenciamento de empréstimo de livros desenvolvido com **Laravel 11/12** no backend e **React (Vite)** no frontend.

---

## 🚀 Tecnologias Necessárias
Antes de começar, você precisa ter instalado na sua máquina:
*   **PHP 8.3** ou superior
*   **Composer**
*   **Node.js 18** ou superior e **npm**
*   **Docker** (para rodar o banco de dados MySQL no container) ou uma instância local do **MySQL** rodando na porta `3307`.

---

## 🛠️ Passo a Passo para Execução

### 1. Configurando o Banco de Dados (Docker)
Este projeto está configurado para utilizar o MySQL rodando via Docker na porta **3307** para evitar conflitos de porta.

Suba o container do banco de dados na raiz do projeto executando:
```bash
docker-compose up -d
```

---

### 2. Configurando o Backend (Laravel)

Na **raiz do projeto**, execute os seguintes comandos:

1.  **Instale as dependências do PHP:**
    ```bash
    composer install
    ```

2.  **Crie o arquivo de configuração `.env`:**
    Copie o modelo padrão para configurar as variáveis de ambiente:
    ```bash
    cp .env.example .env
    ```

3.  **Gere a chave da aplicação:**
    ```bash
    php artisan key:generate
    ```

4.  **Configure o banco de dados e CORS no `.env`:**
    Abra o arquivo `.env` e confirme se os dados de conexão com o banco e o CORS do React estão apontando para as portas corretas:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3307
    DB_DATABASE=gerenciador
    DB_USERNAME=root
    DB_PASSWORD=root

    # URLs permitidas para requisições SPA (Porta 3001 do Vite)
    FRONTEND_URL=http://localhost:3001
    SANCTUM_STATEFUL_DOMAINS=localhost:3001,127.0.0.1:3001
    ```

5.  **Rode as Migrations e os Seeders:**
    Isso criará as tabelas e povoará o banco de dados com os livros e o usuário padrão de teste:
    ```bash
    php artisan migrate --seed
    ```
    *   **Usuário Padrão:** `teste@biblioteca.com`
    *   **Senha:** `senha123`

6.  **Inicie o Servidor do Backend:**
    ```bash
    php artisan serve
    ```
    *(O servidor do Laravel iniciará por padrão em `http://127.0.0.1:8000`)*

---

### 3. Configurando o Frontend (React + Vite)

Abra outro terminal e navegue para a pasta do frontend:

1.  **Entre na pasta do frontend:**
    ```bash
    cd frontend
    ```

2.  **Instale as dependências do npm:**
    ```bash
    npm i
    ```

3.  **Inicie o Servidor de Desenvolvimento:**
    ```bash
    npm run dev
    ```
    *(O servidor do frontend iniciará em `http://localhost:3001`)*

---

## 🧪 Executando os Testes do Backend

Para garantir que a lógica de empréstimos e regras de concorrência estejam funcionando corretamente, execute os testes automáticos na raiz do projeto:
```bash
php artisan test
```

---

## 📖 Regras de Negócio Implementadas
*   O usuário pode pegar no máximo **3 livros emprestados simultaneamente**.
*   Não é possível pegar um livro emprestado caso **não haja mais cópias disponíveis em estoque**.
*   A devolução de um livro incrementa novamente a quantidade do estoque e altera o status do empréstimo para `devolvido`.
