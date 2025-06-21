<?php
// app/Models/Restaurante.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurante extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'endereco',
        'telefone',
        'email',
        'tipo_cozinha',
        'pratos_confeccionados',
        'nota_media',
        'ativo'
    ];

    protected function casts(): array
    {
        return [
            'pratos_confeccionados' => 'array',
            'nota_media' => 'decimal:2',
            'ativo' => 'boolean'
        ];
    }

    public function receitas()
    {
        return $this->belongsToMany(Receita::class, 'restaurante_receitas');
    }

    public function pratosConfeccionados()
    {
        if (empty($this->pratos_confeccionados)) {
            return collect();
        }

        return Receita::whereIn('id', $this->pratos_confeccionados)->get();
    }

    public function getPratosCountAttribute(): int
    {
        return count($this->pratos_confeccionados ?? []);
    }

    public function adicionarPrato(Receita $receita): void
    {
        $pratos = $this->pratos_confeccionados ?? [];
        if (!in_array($receita->id, $pratos)) {
            $pratos[] = $receita->id;
            $this->pratos_confeccionados = $pratos;
            $this->save();
        }
    }

    public function removerPrato(Receita $receita): void
    {
        $pratos = $this->pratos_confeccionados ?? [];
        $this->pratos_confeccionados = array_filter($pratos, fn($id) => $id !== $receita->id);
        $this->save();
    }

    public function scopeAtivo($query)
    {
        return $query->where('ativo', true);
    }

    public function scopePorTipoCozinha($query, $tipo)
    {
        return $query->where('tipo_cozinha', $tipo);
    }
}
