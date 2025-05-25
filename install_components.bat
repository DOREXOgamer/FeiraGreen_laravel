@echo off
echo Verificando se o arquivo .env existe...
if not exist ".env" (
    echo Criando arquivo .env a partir do .env.example...
    copy .env.example .env
) else (
    echo Arquivo .env já existe.
)

echo Instalando dependências PHP com Composer...
composer install

echo Instalando dependências Node.js com npm...
npm install

echo Compilando assets frontend...
npm run dev

echo Executando migrações do banco de dados...
php artisan migrate

echo Criando link simbólico para storage...
php artisan storage:link

echo Instalação concluída com sucesso!

pause