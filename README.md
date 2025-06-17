# 🗂 Kanban Board System with Laravel

![Kanban Screenshot](public/img/screenshot.png)

Um sistema completo de quadro Kanban estilo Trello, desenvolvido com Laravel, Bootstrap 5 e jQuery.

## ✨ Funcionalidades

- ✅ Autenticação de usuários
- ✅ CRUD completo de quadros, colunas e tarefas
- ✅ Drag-and-drop de tarefas entre colunas
- ✅ Design responsivo com tema escuro
- ✅ Prioridade visual de tarefas
- ✅ Seleção de cores personalizáveis
- ✅ Confirmações com SweetAlert2

## 🛠 Tecnologias Utilizadas

- **Backend**: Laravel 10, PHP 8.2
- **Frontend**: Bootstrap 5, jQuery, Font Awesome
- **Bibliotecas**: SweetAlert2, SortableJS
- **Banco de Dados**: PostgreSQL/MySQL
- **Deploy**: (adicione seu método de deploy)

## 🚀 Como Executar Localmente

### Pré-requisitos

- PHP 8.2+
- Composer
- Node.js 16+
- Banco de dados (PostgreSQL/MySQL)

### Instalação

1. Clone o repositório:
- git clone https://github.com/bruno-herculano/laravel-kanban.git
- cd laravel-kanban

2. Instale as dependências
- composer install
- npm install

3. Configure o ambiente
- cp .env.example .env
- php artisan key:generate

4. Configure o banco de dados no .env
- DB_CONNECTION=pgsql
- DB_HOST=127.0.0.1
- DB_PORT=5432 / 3306
- DB_DATABASE=kanban
- DB_USERNAME=postgres / mysql
- DB_PASSWORD=

5. Execute as migrações e os seeder`s
- php artisan migrate --seed

6. Compile os assets
- npm run build
- npm run dev

8. inicie o servidor
- php artisan serve

## 🌟 Dados de Teste

### O sistema inclui um seeder com:

- 1 usuário de teste: teste@kanban.com / password
- 2 quadros de exemplo com colunas e tarefas

## 👨‍💻 Autor

### Bruno Alexandre Herculano

- Portfólio: https://bruno-herculano.dev.br
- LinkedIn: https://www.linkedin.com/in/bruno-herculano
- GitHub: https://github.com/bruno-herculano
