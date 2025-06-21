<?php
// app/Models/Teste.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Teste extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'receita_id',
        'degustador_id',
        'data_teste',
        'status',
        'observacoes_pre_teste',
        'observacoes_pos_teste',
        'fotos_teste'
    ];

    protected function casts(): array
    {
        return [
            'data_teste' => 'datetime',
            'fotos_teste' => 'array'
        ];
    }

    public function receita()
    {
        return $this->belongsTo(Receita::class);
    }

    public function degustador()
    {
        return $this->belongsTo(Degustador::class);
    }

    public function avaliacao()
    {
        return $this->hasOne(Avaliacao::class);
    }

    public function isAgendado(): bool
    {
        return $this->status === 'agendado';
    }

    public function isEmAndamento(): bool
    {
        return $this->status === 'em_andamento';
    }

    public function isConcluido(): bool
    {
        return $this->status === 'concluido';
    }

    public function isCancelado(): bool
    {
        return $this->status === 'cancelado';
    }

    public function podeSerAvaliado(): bool
    {
        return in_array($this->status, ['em_andamento', 'concluido']);
    }

    public function getDataFormatadaAttribute(): string
    {
        return $this->data_teste->format('d/m/Y H:i');
    }

    public function getDiasDaDataTesteAttribute(): int
    {
        return now()->diffInDays($this->data_teste, false);
    }

    public function scopeAgendados($query)
    {
        return $query->where('status', 'agendado');
    }

    public function scopeConcluidos($query)
    {
        return $query->where('status', 'concluido');
    }

    public function scopeEmAndamento($query)
    {
        return $query->where('status', 'em_andamento');
    }

    public function scopePorPeriodo($query, $inicio, $fim)
    {
        return $query->whereBetween('data_teste', [$inicio, $fim]);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('fotos_teste')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }
}
