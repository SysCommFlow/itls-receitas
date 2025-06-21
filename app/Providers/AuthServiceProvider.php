<?php
// app/Providers/AuthServiceProvider.php
namespace App\Providers;

use App\Models\Receita;
use App\Models\Teste;
use App\Models\Ingrediente;
use App\Models\Categoria;
use App\Models\Degustador;
use App\Models\Restaurante;
use App\Models\Editor;
use App\Models\Livro;
use App\Policies\ReceitaPolicy;
use App\Policies\TestePolicy;
use App\Policies\IngredientePolicy;
use App\Policies\CategoriaPolicy;
use App\Policies\DegustadorPolicy;
use App\Policies\RestaurantePolicy;
use App\Policies\EditorPolicy;
use App\Policies\LivroPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     */
    protected $policies = [
        Receita::class => ReceitaPolicy::class,
        Teste::class => TestePolicy::class,
        Ingrediente::class => IngredientePolicy::class,
        Categoria::class => CategoriaPolicy::class,
        Degustador::class => DegustadorPolicy::class,
        Restaurante::class => RestaurantePolicy::class,
        Editor::class => EditorPolicy::class,
        Livro::class => LivroPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
