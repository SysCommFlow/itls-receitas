<?php
// app/Models/Categoria.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'descricao',
        'ativo'
    ];

    protected function casts(): array
    {
        return [
            'ativo' => 'boolean'
        ];
    }

    public function receitas()
    {
        return $this->hasMany(Receita::class);
    }

    public function getReceitasCountAttribute(): int
    {
        return $this->receitas()->count();
    }

    public function getReceitasPublicadasCountAttribute(): int
    {
        return $this->receitas()->where('publicada', true)->count();
    }

    public function scopeAtivo($query)
    {
        return $query->where('ativo', true);
    }
}
