<?php

// app/Http/Requests/DegustadorRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DegustadorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $degustadorId = $this->route('degustador')?->id;

        return [
            'nome' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('degustadores', 'email')->ignore($degustadorId)
            ],
            'telefone' => ['nullable', 'string', 'max:20'],
            'especializacoes' => ['nullable', 'string', 'max:1000'],
            'experiencia_anos' => ['nullable', 'integer', 'min:0', 'max:50'],
            'ativo' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome é obrigatório.',
            'nome.max' => 'O nome não pode ter mais de 255 caracteres.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ser um endereço válido.',
            'email.unique' => 'Este email já está sendo usado por outro degustador.',
            'telefone.max' => 'O telefone não pode ter mais de 20 caracteres.',
            'especializacoes.max' => 'As especializações não podem ter mais de 1000 caracteres.',
            'experiencia_anos.integer' => 'A experiência deve ser um número inteiro.',
            'experiencia_anos.min' => 'A experiência não pode ser negativa.',
            'experiencia_anos.max' => 'A experiência não pode ser superior a 50 anos.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('ativo')) {
            $this->merge(['ativo' => $this->boolean('ativo')]);
        }
    }
}
