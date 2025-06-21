<?php
// app/Models/Livro.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Livro extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'titulo',
        'isbn',
        'editor_id',
        'receitas_incluidas',
        'data_publicacao',
        'descricao',
        'capa',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'receitas_incluidas' => 'array',
            'data_publicacao' => 'date'
        ];
    }

    public function editor()
    {
        return $this->belongsTo(Editor::class);
    }

    public function receitas()
    {
        return $this->belongsToMany(Receita::class, 'livro_receitas');
    }

    public function getReceitasIncluidas()
    {
        if (empty($this->receitas_incluidas)) {
            return collect();
        }

        return Receita::whereIn('id', $this->receitas_incluidas)->get();
    }

    public function getNumeroReceitasAttribute(): int
    {
        return count($this->receitas_incluidas ?? []);
    }

    public function adicionarReceita(Receita $receita): void
    {
        $receitas = $this->receitas_incluidas ?? [];
        if (!in_array($receita->id, $receitas)) {
            $receitas[] = $receita->id;
            $this->receitas_incluidas = $receitas;
            $this->save();
        }
    }

    public function removerReceita(Receita $receita): void
    {
        $receitas = $this->receitas_incluidas ?? [];
        $this->receitas_incluidas = array_filter($receitas, fn($id) => $id !== $receita->id);
        $this->save();
    }

    public function isRascunho(): bool
    {
        return $this->status === 'rascunho';
    }

    public function isEmRevisao(): bool
    {
        return $this->status === 'em_revisao';
    }

    public function isPublicado(): bool
    {
        return $this->status === 'publicado';
    }

    public function podeSerEditado(): bool
    {
        return in_array($this->status, ['rascunho', 'em_revisao']);
    }

    public function scopePublicados($query)
    {
        return $query->where('status', 'publicado');
    }

    public function scopeEmAndamento($query)
    {
        return $query->whereIn('status', ['rascunho', 'em_revisao']);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('capa')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }
}
