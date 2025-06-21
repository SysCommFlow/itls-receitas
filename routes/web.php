<?php

/*
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
*/

// routes/web.php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReceitaController;
use App\Http\Controllers\TesteController;
use App\Http\Controllers\IngredienteController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DegustadorController;
use App\Http\Controllers\RestauranteController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    //return redirect()->route('login');
    return Inertia::render('Welcome');
})->name('home');

// Rotas autenticadas
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil do usuário
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Receitas - Todos os usuários autenticados
    Route::resource('receitas', ReceitaController::class);
    Route::patch('/receitas/{receita}/toggle-publicacao', [ReceitaController::class, 'togglePublicacao'])
        ->name('receitas.toggle-publicacao');

    // Testes - Cozinheiros e Admins podem criar, todos podem ver
    Route::resource('testes', TesteController::class);
    Route::get('/testes/{teste}/avaliar', [TesteController::class, 'avaliar'])->name('testes.avaliar');
    Route::post('/testes/{teste}/avaliacao', [TesteController::class, 'salvarAvaliacao'])
        ->name('testes.salvar-avaliacao');
    Route::patch('/testes/{teste}/status', [TesteController::class, 'updateStatus'])
        ->name('testes.update-status');

    // Rotas apenas para Admins
    Route::middleware(['user.type:admin'])->group(function () {
        // Ingredientes
        Route::resource('ingredientes', IngredienteController::class);

        // Categorias
        Route::resource('categorias', CategoriaController::class);
        Route::patch('/categorias/{categoria}/toggle-status', [CategoriaController::class, 'toggleStatus'])
            ->name('categorias.toggle-status');

        // Degustadores
        Route::resource('degustadores', DegustadorController::class);
        Route::patch('/degustadores/{degustador}/toggle-status', [DegustadorController::class, 'toggleStatus'])
            ->name('degustadores.toggle-status');

        // Restaurantes
        Route::resource('restaurantes', RestauranteController::class);
        Route::patch('/restaurantes/{restaurante}/toggle-status', [RestauranteController::class, 'toggleStatus'])
            ->name('restaurantes.toggle-status');
        Route::post('/restaurantes/{restaurante}/adicionar-prato', [RestauranteController::class, 'adicionarPrato'])
            ->name('restaurantes.adicionar-prato');
        Route::delete('/restaurantes/{restaurante}/remover-prato/{receita}', [RestauranteController::class, 'removerPrato'])
            ->name('restaurantes.remover-prato');

        // Editores
        Route::resource('editores', EditorController::class);
        Route::patch('/editores/{editor}/toggle-status', [EditorController::class, 'toggleStatus'])
            ->name('editores.toggle-status');

        // Livros
        Route::resource('livros', LivroController::class);
        Route::patch('/livros/{livro}/status', [LivroController::class, 'updateStatus'])
            ->name('livros.update-status');
        Route::post('/livros/{livro}/adicionar-receita', [LivroController::class, 'adicionarReceita'])
            ->name('livros.adicionar-receita');
        Route::delete('/livros/{livro}/remover-receita/{receita}', [LivroController::class, 'removerReceita'])
            ->name('livros.remover-receita');
    });

    // Relatórios - Todos os usuários autenticados
    Route::prefix('relatorios')->name('relatorios.')->group(function () {
        Route::get('/', [RelatorioController::class, 'index'])->name('index');
        Route::get('/cozinheiros', [RelatorioController::class, 'receitasPorCozinheiro'])->name('cozinheiros');
        Route::get('/testes', [RelatorioController::class, 'testesPorPeriodo'])->name('testes');
        Route::get('/restaurantes', [RelatorioController::class, 'restaurantesEPratos'])->name('restaurantes');
        Route::get('/estatisticas', [RelatorioController::class, 'estatisticasGerais'])->name('estatisticas');
        Route::get('/export/pdf', [RelatorioController::class, 'exportarPdf'])->name('export.pdf');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
