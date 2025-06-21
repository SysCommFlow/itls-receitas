<?php
// app/Models/Degustador.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Degustador extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'degustadores';

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'especializacoes',
        'experiencia_anos',
        'nota_media_avaliacoes',
        'ativo'
    ];

    protected function casts(): array
    {
        return [
            'nota_media_avaliacoes' => 'decimal:2',
            'ativo' => 'boolean'
        ];
    }

    public function testes()
    {
        return $this->hasMany(Teste::class);
    }

    public function avaliacoes()
    {
        return $this->hasManyThrough(Avaliacao::class, Teste::class);
    }

    public function calcularNotaMedia(): void
    {
        $avaliacoes = $this->avaliacoes();
        if ($avaliacoes->count() > 0) {
            $this->nota_media_avaliacoes = $avaliacoes->avg('nota_geral');
            $this->save();
        }
    }

    public function getTestesConcluidosCountAttribute(): int
    {
        return $this->testes()->where('status', 'concluido')->count();
    }

    public function getTestesPendentesCountAttribute(): int
    {
        return $this->testes()->where('status', 'agendado')->count();
    }

    public function scopeAtivo($query)
    {
        return $query->where('ativo', true);
    }

    public function scopeComExperiencia($query, $anosMinimos)
    {
        return $query->where('experiencia_anos', '>=', $anosMinimos);
    }
}
