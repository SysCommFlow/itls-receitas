<?php

// app/Http/Requests/EditorRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $editorId = $this->route('editor')?->id;

        return [
            'nome' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('editores', 'email')->ignore($editorId)
            ],
            'telefone' => ['nullable', 'string', 'max:20'],
            'editora' => ['required', 'string', 'max:255'],
            'especializacoes' => ['nullable', 'string', 'max:1000'],
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
            'email.unique' => 'Este email já está sendo usado por outro editor.',
            'telefone.max' => 'O telefone não pode ter mais de 20 caracteres.',
            'editora.required' => 'A editora é obrigatória.',
            'editora.max' => 'A editora não pode ter mais de 255 caracteres.',
            'especializacoes.max' => 'As especializações não podem ter mais de 1000 caracteres.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('ativo')) {
            $this->merge(['ativo' => $this->boolean('ativo')]);
        }
    }
}
