<?php
// app/Http/Requests/IngredienteRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IngredienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $ingredienteId = $this->route('ingrediente')?->id;

        return [
            'nome' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ingredientes', 'nome')->ignore($ingredienteId)
            ],
            'descricao' => ['nullable', 'string', 'max:1000'],
            'unidade_medida' => ['required', 'string', 'max:50'],
            'preco_medio' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'exotico' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do ingrediente é obrigatório.',
            'nome.unique' => 'Já existe um ingrediente com este nome.',
            'nome.max' => 'O nome não pode ter mais de 255 caracteres.',
            'descricao.max' => 'A descrição não pode ter mais de 1000 caracteres.',
            'unidade_medida.required' => 'A unidade de medida é obrigatória.',
            'unidade_medida.max' => 'A unidade de medida não pode ter mais de 50 caracteres.',
            'preco_medio.numeric' => 'O preço médio deve ser um número.',
            'preco_medio.min' => 'O preço médio deve ser maior ou igual a zero.',
            'preco_medio.max' => 'O preço médio não pode ser superior a R$ 999.999,99.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('exotico')) {
            $this->merge(['exotico' => $this->boolean('exotico')]);
        }
    }
}
