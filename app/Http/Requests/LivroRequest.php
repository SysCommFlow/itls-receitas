<?php
// app/Http/Requests/LivroRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LivroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $livroId = $this->route('livro')?->id;

        return [
            'titulo' => ['required', 'string', 'max:255'],
            'isbn' => [
                'required',
                'string',
                'max:17', // ISBN-13 com hífens
                Rule::unique('livros', 'isbn')->ignore($livroId)
            ],
            'editor_id' => ['required', 'exists:editores,id'],
            'receitas_incluidas' => ['nullable', 'array'],
            'receitas_incluidas.*' => ['exists:receitas,id'],
            'data_publicacao' => ['nullable', 'date'],
            'descricao' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', Rule::in(['rascunho', 'em_revisao', 'publicado'])],
            'capa' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,png,webp'],
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'O título é obrigatório.',
            'titulo.max' => 'O título não pode ter mais de 255 caracteres.',
            'isbn.required' => 'O ISBN é obrigatório.',
            'isbn.unique' => 'Este ISBN já está sendo usado por outro livro.',
            'isbn.max' => 'O ISBN não pode ter mais de 17 caracteres.',
            'editor_id.required' => 'O editor é obrigatório.',
            'editor_id.exists' => 'O editor selecionado não existe.',
            'receitas_incluidas.*.exists' => 'Uma das receitas selecionadas não existe.',
            'data_publicacao.date' => 'A data de publicação deve ser uma data válida.',
            'descricao.max' => 'A descrição não pode ter mais de 2000 caracteres.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser: rascunho, em revisão ou publicado.',
            'capa.image' => 'A capa deve ser uma imagem.',
            'capa.max' => 'A capa não pode ser maior que 2MB.',
            'capa.mimes' => 'A capa deve ser do tipo: jpeg, png ou webp.',
        ];
    }
}
