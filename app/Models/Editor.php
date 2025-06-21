<?php
// app/Models/Editor.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Editor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'editores';

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'editora',
        'especializacoes',
        'ativo'
    ];

    protected function casts(): array
    {
        return [
            'ativo' => 'boolean'
        ];
    }

    public function livros()
    {
        return $this->hasMany(Livro::class);
    }

    public function getLivrosPublicadosCountAttribute(): int
    {
        return $this->livros()->where('status', 'publicado')->count();
    }

    public function getLivrosEmAndamentoCountAttribute(): int
    {
        return $this->livros()->whereIn('status', ['rascunho', 'em_revisao'])->count();
    }

    public function scopeAtivo($query)
    {
        return $query->where('ativo', true);
    }
}
