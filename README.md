# Projeto FeiraGreen Laravel

## Visão Geral

Este projeto é uma aplicação web baseada em Laravel para gerenciar um marketplace com perfis de usuários, listagem de produtos e autenticação de usuários. As principais funcionalidades incluem:

- Registro e login de usuários com validação e mensagens de erro em português.
- Página de perfil do usuário com opções para atualizar nome, enviar imagem de perfil, adicionar produtos e sair.
- Gerenciamento de produtos com funcionalidades de adicionar, editar e excluir, incluindo upload de imagens.
- Exclusão de conta de usuário com limpeza dos produtos e imagens associadas.
- Página inicial organizada exibindo promoções de produtos com imagens e detalhes.
- Armazenamento de imagens centralizado em `storage/app/public/profile_images`.

## Instruções de Configuração

### Pré-requisitos

- PHP >= 8.0
- Composer
- SQLite (ou outro banco de dados suportado)
- Node.js e npm (para assets frontend)
- Git

### Passos para Instalação

1. **Clonar o repositório**

```bash
git clone https://github.com/DOREXOgamer/Feira_green_Laravel.git
cd Feira_green_Laravel
```

2. **Instalar dependências PHP**

```bash
composer install
```

3. **Instalar dependências Node.js e compilar assets**

```bash
npm install
npm run dev
```

4. **Configurar ambiente**

Copie `.env.example` para `.env` e configure as definições do banco de dados. Como você está usando o phpMyAdmin do XAMPP, configure o banco de dados MySQL conforme abaixo:

Atualize o `.env` com as configurações do MySQL:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco_de_dados
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

Certifique-se de criar o banco de dados no phpMyAdmin antes de executar as migrações.

5. **Executar as migrações**

```bash
php artisan migrate
```

6. **Criar link simbólico para storage**

```bash
php artisan storage:link
```

7. **Executar o servidor de desenvolvimento**

```bash
php artisan serve
```

A aplicação estará acessível em `http://localhost:8000`.

## Uso

- Registre uma nova conta de usuário.
- Acesse a página de perfil para atualizar seu nome e imagem de perfil.
- Adicione produtos com imagens e gerencie-os.
- Navegue pela página inicial para ver promoções de produtos.
- Exclua sua conta, se desejar.

## Referências

- [Documentação Laravel](https://laravel.com/docs)
- [Armazenamento de Arquivos Laravel](https://laravel.com/docs/filesystem)
- [Blade Templating](https://laravel.com/docs/blade)
- [Bootstrap 5](https://getbootstrap.com/docs/5.0/getting-started/introduction/)
- [Documentação Git](https://git-scm.com/doc)

## Notas

- As imagens são armazenadas em `storage/app/public/profile_images`. Certifique-se de criar o link simbólico do storage.
- Mensagens de erro de validação estão traduzidas para português para melhor experiência do usuário.
- Relacionamentos entre usuário e produto são gerenciados com chaves estrangeiras e migrações.

Se encontrar algum problema ou tiver dúvidas, por favor abra uma issue ou contate o mantenedor.

---
touch database/database.sqlite


## CASO DE ERRO 
```bash
php artisan ui bootstrap --auth
```
nao modifique nada 
opçao "no" em todos.#
