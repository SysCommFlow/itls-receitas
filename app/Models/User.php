<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, HasRoles, InteractsWithMedia;

    protected $fillable = [
        'name',
        'email',
        'password',
        'tipo_usuario',
        'telefone',
        'data_nascimento',
        'bio',
        'foto_perfil',
        'especializacoes',
        'ativo',
        'ultimo_acesso'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'data_nascimento' => 'date',
            'especializacoes' => 'array',
            'ativo' => 'boolean',
            'ultimo_acesso' => 'datetime'
        ];
    }

    public function receitas()
    {
        return $this->hasMany(Receita::class);
    }

    public function isCozinheiro(): bool
    {
        return $this->tipo_usuario === 'cozinheiro';
    }

    public function isDegustador(): bool
    {
        return $this->tipo_usuario === 'degustador';
    }

    public function isAdmin(): bool
    {
        return $this->tipo_usuario === 'admin';
    }

    public function getReceitasPublicadasCountAttribute(): int
    {
        return $this->receitas()->where('publicada', true)->count();
    }

    public function getReceitasTestadasCountAttribute(): int
    {
        return $this->receitas()->where('testada', true)->count();
    }

    public function getNotaMediaReceitasAttribute(): ?float
    {
        return $this->receitas()
            ->whereNotNull('nota_media')
            ->avg('nota_media');
    }
}
