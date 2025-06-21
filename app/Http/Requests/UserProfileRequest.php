<?php
// app/Http/Requests/UserProfileRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'telefone' => ['nullable', 'string', 'max:20'],
            'data_nascimento' => ['nullable', 'date', 'before:today'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'especializacoes' => ['nullable', 'array'],
            'especializacoes.*' => ['string', 'max:100'],
            'foto_perfil' => ['nullable', 'image', 'max:1024', 'mimes:jpeg,png,webp'], // máximo 1MB
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ser um endereço válido.',
            'email.unique' => 'Este email já está sendo usado.',
            'telefone.max' => 'O telefone não pode ter mais de 20 caracteres.',
            'data_nascimento.date' => 'A data de nascimento deve ser uma data válida.',
            'data_nascimento.before' => 'A data de nascimento deve ser anterior a hoje.',
            'bio.max' => 'A biografia não pode ter mais de 1000 caracteres.',
            'especializacoes.*.max' => 'Cada especialização não pode ter mais de 100 caracteres.',
            'foto_perfil.image' => 'A foto deve ser uma imagem.',
            'foto_perfil.max' => 'A foto não pode ser maior que 1MB.',
            'foto_perfil.mimes' => 'A foto deve ser do tipo: jpeg, png ou webp.',
        ];
    }
}
