<?php

// app/Http/Requests/RestauranteRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RestauranteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'endereco' => ['required', 'string', 'max:500'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'tipo_cozinha' => ['required', 'string', 'max:100'],
            'ativo' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do restaurante é obrigatório.',
            'nome.max' => 'O nome não pode ter mais de 255 caracteres.',
            'endereco.required' => 'O endereço é obrigatório.',
            'endereco.max' => 'O endereço não pode ter mais de 500 caracteres.',
            'telefone.max' => 'O telefone não pode ter mais de 20 caracteres.',
            'email.email' => 'O email deve ser um endereço válido.',
            'email.max' => 'O email não pode ter mais de 255 caracteres.',
            'tipo_cozinha.required' => 'O tipo de cozinha é obrigatório.',
            'tipo_cozinha.max' => 'O tipo de cozinha não pode ter mais de 100 caracteres.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('ativo')) {
            $this->merge(['ativo' => $this->boolean('ativo')]);
        }
    }
}
