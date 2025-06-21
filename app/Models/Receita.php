<?php
// app/Models/Receita.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Receita extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'codigo_unico',
        'nome',
        'user_id',
        'categoria_id',
        'modo_preparacao',
        'tempo_cozimento',
        'numero_porcoes',
        'observacoes',
        'imagens',
        'publicada',
        'testada',
        'nota_media'
    ];

    protected function casts(): array
    {
        return [
            'imagens' => 'array',
            'publicada' => 'boolean',
            'testada' => 'boolean',
            'nota_media' => 'decimal:2'
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($receita) {
            if (empty($receita->codigo_unico)) {
                $receita->codigo_unico = 'REC-' . strtoupper(Str::random(8));
            }
        });
    }

    // Relacionamentos
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function ingredientes()
    {
        return $this->belongsToMany(Ingrediente::class, 'receita_ingredientes')
                    ->withPivot('quantidade', 'unidade', 'observacoes')
                    ->withTimestamps();
    }

    public function testes()
    {
        return $this->hasMany(Teste::class);
    }

    public function avaliacoes()
    {
        return $this->hasManyThrough(Avaliacao::class, Teste::class);
    }

    public function restaurantes()
    {
        return $this->belongsToMany(Restaurante::class, 'restaurante_receitas');
    }

    public function livros()
    {
        return $this->belongsToMany(Livro::class, 'livro_receitas');
    }

    // Métodos auxiliares
    public function calcularNotaMedia(): void
    {
        $avaliacoes = $this->avaliacoes();
        if ($avaliacoes->count() > 0) {
            $this->nota_media = $avaliacoes->avg('nota_geral');
            $this->testada = true;
            $this->save();
        }
    }

    public function getTempoFormatadoAttribute(): string
    {
        $horas = intval($this->tempo_cozimento / 60);
        $minutos = $this->tempo_cozimento % 60;

        if ($horas > 0) {
            return "{$horas}h {$minutos}min";
        }
        return "{$minutos}min";
    }

    public function getCustoEstimadoAttribute(): float
    {
        return $this->ingredientes->sum(function ($ingrediente) {
            return $ingrediente->pivot->quantidade * ($ingrediente->preco_medio ?? 0);
        });
    }

    public function getClassificacaoAttribute(): string
    {
        if (!$this->nota_media) return 'Não avaliada';

        if ($this->nota_media >= 9) return 'Excelente';
        if ($this->nota_media >= 8) return 'Muito Bom';
        if ($this->nota_media >= 7) return 'Bom';
        if ($this->nota_media >= 6) return 'Regular';
        return 'Ruim';
    }

    // Scopes
    public function scopePublicadas($query)
    {
        return $query->where('publicada', true);
    }

    public function scopeTestadas($query)
    {
        return $query->where('testada', true);
    }

    public function scopePorCategoria($query, $categoriaId)
    {
        return $query->where('categoria_id', $categoriaId);
    }

    public function scopeBuscar($query, $termo)
    {
        return $query->where(function ($q) use ($termo) {
            $q->where('nome', 'like', "%{$termo}%")
              ->orWhere('codigo_unico', 'like', "%{$termo}%")
              ->orWhere('modo_preparacao', 'like', "%{$termo}%");
        });
    }

    // Configurações do Spatie Media Library
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('imagens')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->singleFile(false);
    }

    /**
     * Configurar conversões de mídia (thumbnails, etc.)
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('imagens');

        $this->addMediaConversion('preview')
            ->width(800)
            ->height(600)
            ->sharpen(10)
            ->optimize()
            ->performOnCollections('imagens');
    }

    /**
     * Getter para URLs das imagens formatadas
     */
    public function getImagensUrlsAttribute()
    {
        return $this->getMedia('imagens')->map(function ($media) {
            return [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'thumb' => $media->getUrl('thumb'),
                'preview' => $media->getUrl('preview'),
            ];
        });
    }

    /**
     * Verificar se o usuário pode editar esta receita
     */
    public function podeSerEditadaPor(User $user): bool
    {
        if ($user->tipo_usuario === 'admin') {
            return true;
        }

        if ($user->tipo_usuario === 'cozinheiro') {
            return $this->user_id === $user->id;
        }

        return false;
    }

    /**
     * Verificar se o usuário pode ver esta receita
     */
    public function podeSerVistaPor(User $user): bool
    {
        if ($user->tipo_usuario === 'admin') {
            return true;
        }

        if ($user->tipo_usuario === 'cozinheiro') {
            return $this->user_id === $user->id || $this->publicada;
        }

        if ($user->tipo_usuario === 'degustador') {
            return $this->publicada;
        }

        return false;
    }

    /**
     * Alternar status de publicação
     */
    public function togglePublicacao(): void
    {
        $this->update(['publicada' => !$this->publicada]);
    }
}
