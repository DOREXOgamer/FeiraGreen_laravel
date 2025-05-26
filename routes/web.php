<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoriaController;


// Rotas Públicas
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/cadastro', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/cadastro', [RegisterController::class, 'register'])->name('register.post');

// Rotas Protegidas
    Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/', [HomeController::class, 'home'])->name('home');

    //busca
    Route::get('/buscar', [HomeController::class, 'buscar'])->name('buscar');

    // Categorias
    Route::get('/categoria/{categoria}', [CategoriaController::class, 'exibirPorCategoria'])
        ->name('categoria.exibir');


    
    // Profile routes
    Route::get('/perfil', [HomeController::class, 'perfil'])->name('perfil');
    Route::post('/perfil/update', [HomeController::class, 'updatePerfil'])->name('perfil.update');
    Route::post('/produto/add', [HomeController::class, 'addProduto'])->name('produto.add');
    Route::get('/produto/{id}', [HomeController::class, 'showProduto'])->name('produto.show');
    Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

    // Cart routes
    Route::get('/carrinho', [CartController::class, 'index'])->name('cart.index');
    Route::post('/carrinho/adicionar/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/carrinho/atualizar/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/carrinho/remover/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Product edit/delete routes
    Route::get('/produto/editar/{id}', [HomeController::class, 'editProduto'])->name('produto.edit');
    Route::put('/produto/atualizar/{id}', [HomeController::class, 'updateProduto'])->name('produto.update');
    Route::delete('/produto/deletar/{id}', [HomeController::class, 'deleteProduto'])->name('produto.delete');

    // User account deletion
    Route::delete('/perfil/deletar', [HomeController::class, 'deleteAccount'])->name('perfil.delete');

        Route::get('/meus-produtos', [HomeController::class, 'indexProdutos'])
         ->name('produto.index');

    // Rotas já existentes de CRUD:
    Route::get('/produto/criar',          [HomeController::class, 'showCreateForm'])->name('produto.create');
    Route::post('/produto/add',           [HomeController::class, 'addProduto'])      ->name('produto.add');
    Route::get('/produto/{id}',           [HomeController::class, 'showProduto'])     ->name('produto.show');
    Route::get('/produto/editar/{id}',    [HomeController::class, 'editProduto'])     ->name('produto.edit');
    Route::put('/produto/atualizar/{id}', [HomeController::class, 'updateProduto'])   ->name('produto.update');
    Route::delete('/produto/deletar/{id}',[HomeController::class, 'deleteProduto'])   ->name('produto.delete');

    
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'home'])->name('home');
});

Auth::routes();

