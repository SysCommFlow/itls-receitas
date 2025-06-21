<?php
// app/Http/Requests/ReceitaRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReceitaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'modo_preparacao' => ['required', 'string', 'min:50'],
            'tempo_cozimento' => ['required', 'integer', 'min:1', 'max:1440'], // máximo 24 horas
            'numero_porcoes' => ['required', 'integer', 'min:1', 'max:100'],
            'observacoes' => ['nullable', 'string', 'max:2000'],
            'publicada' => ['boolean'],
            'ingredientes' => ['required', 'array', 'min:1'],
            'ingredientes.*.id' => ['required', 'exists:ingredientes,id'],
            'ingredientes.*.quantidade' => ['required', 'numeric', 'min:0'],
            'ingredientes.*.unidade' => ['required', 'string', 'max:50'],
            'ingredientes.*.observacoes' => ['nullable', 'string', 'max:500'],
            'imagens.*' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,png,webp'], // máximo 2MB
            'novas_imagens.*' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,png,webp'],
            'imagens_remover' => ['nullable', 'array'],
            'imagens_remover.*' => ['integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome da receita é obrigatório.',
            'nome.max' => 'O nome da receita não pode ter mais de 255 caracteres.',
            'categoria_id.required' => 'A categoria é obrigatória.',
            'categoria_id.exists' => 'A categoria selecionada não existe.',
            'modo_preparacao.required' => 'O modo de preparação é obrigatório.',
            'modo_preparacao.min' => 'O modo de preparação deve ter pelo menos 50 caracteres.',
            'tempo_cozimento.required' => 'O tempo de cozimento é obrigatório.',
            'tempo_cozimento.integer' => 'O tempo de cozimento deve ser um número inteiro.',
            'tempo_cozimento.min' => 'O tempo de cozimento deve ser de pelo menos 1 minuto.',
            'tempo_cozimento.max' => 'O tempo de cozimento não pode ser superior a 24 horas.',
            'numero_porcoes.required' => 'O número de porções é obrigatório.',
            'numero_porcoes.integer' => 'O número de porções deve ser um número inteiro.',
            'numero_porcoes.min' => 'O número de porções deve ser de pelo menos 1.',
            'numero_porcoes.max' => 'O número de porções não pode ser superior a 100.',
            'ingredientes.required' => 'É necessário adicionar pelo menos um ingrediente.',
            'ingredientes.min' => 'É necessário adicionar pelo menos um ingrediente.',
            'ingredientes.*.id.required' => 'O ingrediente é obrigatório.',
            'ingredientes.*.id.exists' => 'O ingrediente selecionado não existe.',
            'ingredientes.*.quantidade.required' => 'A quantidade do ingrediente é obrigatória.',
            'ingredientes.*.quantidade.numeric' => 'A quantidade deve ser um número.',
            'ingredientes.*.quantidade.min' => 'A quantidade deve ser maior que zero.',
            'ingredientes.*.unidade.required' => 'A unidade de medida é obrigatória.',
            'ingredientes.*.unidade.max' => 'A unidade de medida não pode ter mais de 50 caracteres.',
            'imagens.*.image' => 'O arquivo deve ser uma imagem.',
            'imagens.*.max' => 'A imagem não pode ser maior que 2MB.',
            'imagens.*.mimes' => 'A imagem deve ser do tipo: jpeg, png ou webp.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('publicada')) {
            $this->merge(['publicada' => $this->boolean('publicada')]);
        }
    }
}

// app/Http/Requests/TesteRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TesteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receita_id' => ['required', 'exists:receitas,id'],
            'degustador_id' => ['required', 'exists:degustadores,id'],
            'data_teste' => ['required', 'date', 'after:now'],
            'observacoes_pre_teste' => ['nullable', 'string', 'max:1000'],
            'status' => ['sometimes', Rule::in(['agendado', 'em_andamento', 'concluido', 'cancelado'])],
        ];
    }

    public function messages(): array
    {
        return [
            'receita_id.required' => 'A receita é obrigatória.',
            'receita_id.exists' => 'A receita selecionada não existe.',
            'degustador_id.required' => 'O degustador é obrigatório.',
            'degustador_id.exists' => 'O degustador selecionado não existe.',
            'data_teste.required' => 'A data do teste é obrigatória.',
            'data_teste.date' => 'A data do teste deve ser uma data válida.',
            'data_teste.after' => 'A data do teste deve ser futura.',
            'observacoes_pre_teste.max' => 'As observações não podem ter mais de 1000 caracteres.',
            'status.in' => 'O status selecionado é inválido.',
        ];
    }
}

// app/Http/Requests/AvaliacaoRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AvaliacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nota_sabor' => ['required', 'numeric', 'between:0,10'],
            'nota_apresentacao' => ['required', 'numeric', 'between:0,10'],
            'nota_aroma' => ['required', 'numeric', 'between:0,10'],
            'nota_textura' => ['required', 'numeric', 'between:0,10'],
            'comentarios' => ['nullable', 'string', 'max:2000'],
            'recomenda' => ['required', 'boolean'],
            'sugestoes_melhoria' => ['nullable', 'array'],
            'sugestoes_melhoria.*' => ['string', 'max:500'],
            'observacoes_pos_teste' => ['nullable', 'string', 'max:1000'],
            'fotos_teste.*' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,png,webp'],
        ];
    }

    public function messages(): array
    {
        return [
            'nota_sabor.required' => 'A nota de sabor é obrigatória.',
            'nota_sabor.numeric' => 'A nota de sabor deve ser um número.',
            'nota_sabor.between' => 'A nota de sabor deve estar entre 0 e 10.',
            'nota_apresentacao.required' => 'A nota de apresentação é obrigatória.',
            'nota_apresentacao.numeric' => 'A nota de apresentação deve ser um número.',
            'nota_apresentacao.between' => 'A nota de apresentação deve estar entre 0 e 10.',
            'nota_aroma.required' => 'A nota de aroma é obrigatória.',
            'nota_aroma.numeric' => 'A nota de aroma deve ser um número.',
            'nota_aroma.between' => 'A nota de aroma deve estar entre 0 e 10.',
            'nota_textura.required' => 'A nota de textura é obrigatória.',
            'nota_textura.numeric' => 'A nota de textura deve ser um número.',
            'nota_textura.between' => 'A nota de textura deve estar entre 0 e 10.',
            'comentarios.max' => 'Os comentários não podem ter mais de 2000 caracteres.',
            'recomenda.required' => 'É obrigatório informar se recomenda a receita.',
            'recomenda.boolean' => 'O campo de recomendação deve ser verdadeiro ou falso.',
            'sugestoes_melhoria.*.max' => 'Cada sugestão não pode ter mais de 500 caracteres.',
            'observacoes_pos_teste.max' => 'As observações não podem ter mais de 1000 caracteres.',
            'fotos_teste.*.image' => 'O arquivo deve ser uma imagem.',
            'fotos_teste.*.max' => 'A imagem não pode ser maior que 2MB.',
            'fotos_teste.*.mimes' => 'A imagem deve ser do tipo: jpeg, png ou webp.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Filtrar sugestões vazias
        if ($this->has('sugestoes_melhoria')) {
            $sugestoes = array_filter($this->input('sugestoes_melhoria'), function ($sugestao) {
                return !empty(trim($sugestao));
            });
            $this->merge(['sugestoes_melhoria' => array_values($sugestoes)]);
        }

        if ($this->has('recomenda')) {
            $this->merge(['recomenda' => $this->boolean('recomenda')]);
        }
    }

}
