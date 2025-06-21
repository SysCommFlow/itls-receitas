<?php
// app/Models/Ingrediente.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingrediente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'descricao',
        'unidade_medida',
        'preco_medio',
        'exotico'
    ];

    protected function casts(): array
    {
        return [
            'preco_medio' => 'decimal:2',
            'exotico' => 'boolean'
        ];
    }

    public function receitas()
    {
        return $this->belongsToMany(Receita::class, 'receita_ingredientes')
                    ->withPivot('quantidade', 'unidade', 'observacoes')
                    ->withTimestamps();
    }

    public function getReceitasCountAttribute(): int
    {
        return $this->receitas()->count();
    }

    public function scopeExotico($query)
    {
        return $query->where('exotico', true);
    }

    public function scopeComum($query)
    {
        return $query->where('exotico', false);
    }
}
