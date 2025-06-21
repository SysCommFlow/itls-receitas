<?php
// app/Models/Avaliacao.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    use HasFactory;

    protected $table = 'avaliacoes';

    protected $fillable = [
        'teste_id',
        'nota_sabor',
        'nota_apresentacao',
        'nota_aroma',
        'nota_textura',
        'nota_geral',
        'comentarios',
        'recomenda',
        'sugestoes_melhoria'
    ];

    protected function casts(): array
    {
        return [
            'nota_sabor' => 'decimal:2',
            'nota_apresentacao' => 'decimal:2',
            'nota_aroma' => 'decimal:2',
            'nota_textura' => 'decimal:2',
            'nota_geral' => 'decimal:2',
            'recomenda' => 'boolean',
            'sugestoes_melhoria' => 'array'
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($avaliacao) {
            // Calcular nota geral automaticamente
            $avaliacao->nota_geral = (
                $avaliacao->nota_sabor +
                $avaliacao->nota_apresentacao +
                $avaliacao->nota_aroma +
                $avaliacao->nota_textura
            ) / 4;
        });

        static::saved(function ($avaliacao) {
            // Atualizar nota média da receita
            $avaliacao->teste->receita->calcularNotaMedia();
            // Atualizar nota média do degustador
            $avaliacao->teste->degustador->calcularNotaMedia();
        });
    }

    public function teste()
    {
        return $this->belongsTo(Teste::class);
    }

    public function receita()
    {
        return $this->hasOneThrough(
            Receita::class,
            Teste::class,
            'id',
            'id',
            'teste_id',
            'receita_id'
        );
    }

    public function degustador()
    {
        return $this->hasOneThrough(
            Degustador::class,
            Teste::class,
            'id',
            'id',
            'teste_id',
            'degustador_id'
        );
    }

    public function getNotaGeralFormatadaAttribute(): string
    {
        return number_format($this->nota_geral, 1);
    }

    public function getClassificacaoAttribute(): string
    {
        if ($this->nota_geral >= 9) return 'Excelente';
        if ($this->nota_geral >= 8) return 'Muito Bom';
        if ($this->nota_geral >= 7) return 'Bom';
        if ($this->nota_geral >= 6) return 'Regular';
        return 'Ruim';
    }

    public function getCorClassificacaoAttribute(): string
    {
        if ($this->nota_geral >= 9) return 'text-green-600';
        if ($this->nota_geral >= 8) return 'text-blue-600';
        if ($this->nota_geral >= 7) return 'text-yellow-600';
        if ($this->nota_geral >= 6) return 'text-orange-600';
        return 'text-red-600';
    }

    public function scopeRecomendadas($query)
    {
        return $query->where('recomenda', true);
    }

    public function scopeComNotaMinima($query, $notaMinima)
    {
        return $query->where('nota_geral', '>=', $notaMinima);
    }
}
