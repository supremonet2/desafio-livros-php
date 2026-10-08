<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLivroRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $valor = $this->input('valor');

        if (! is_string($valor)) {
            return;
        }

        $valor = trim($valor);

        if (preg_match('/^(?:R\$\s*)?(?:\d{1,3}(?:\.\d{3})+|\d+)(?:,\d{1,2})?$/u', $valor) !== 1) {
            return;
        }

        $valor = preg_replace('/^R\$\s*/u', '', $valor);
        $this->merge([
            'valor' => str_replace(',', '.', str_replace('.', '', $valor)),
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'Codl' => ['required', 'integer', 'min:1', 'max:2147483647', 'unique:livros,Codl'],
            'titulo' => ['required', 'string', 'max:40'],
            'editora' => ['required', 'string', 'max:40'],
            'edicao' => ['required', 'integer', 'min:1'],
            'ano' => ['required', 'integer', 'digits:4', 'min:1000', 'max:'.date('Y')],
            'valor' => ['required', 'numeric', 'min:0', 'max:99999999.99', 'decimal:0,2'],

            'autores' => ['required', 'array', 'min:1'],
            'autores.*' => ['required', 'integer', 'distinct', 'exists:autors,CodAu'],

            'assuntos' => ['required', 'array', 'min:1'],
            'assuntos.*' => ['required', 'integer', 'distinct', 'exists:assuntos,CodAs'],
        ];
    }

    public function messages(): array
    {
        return [
            'Codl.required' => 'Informe o código do livro.',
            'Codl.integer' => 'O código do livro deve ser um número inteiro.',
            'Codl.min' => 'O código do livro deve ser maior que zero.',
            'Codl.max' => 'O código do livro é muito grande.',
            'Codl.unique' => 'Este código de livro já está em uso.',
            'titulo.required' => 'Informe o título do livro.',
            'titulo.max' => 'O título deve ter no máximo 40 caracteres.',

            'editora.required' => 'Informe a editora.',
            'editora.max' => 'A editora deve ter no máximo 40 caracteres.',

            'edicao.required' => 'Informe a edição.',
            'edicao.integer' => 'A edição deve ser um número inteiro.',
            'edicao.min' => 'A edição deve ser maior que zero.',

            'ano.required' => 'Informe o ano de publicação.',
            'ano.integer' => 'O ano deve ser um número inteiro.',
            'ano.digits' => 'O ano deve ter quatro dígitos.',
            'ano.min' => 'O ano deve ser igual ou posterior a 1000.',
            'ano.max' => 'O ano não pode ser posterior ao ano atual.',

            'valor.required' => 'Informe o valor.',
            'valor.numeric' => 'O valor deve ser numérico.',
            'valor.min' => 'O valor não pode ser negativo.',
            'valor.max' => 'O valor deve ser de até R$ 99.999.999,99.',
            'valor.decimal' => 'O valor deve ter no máximo duas casas decimais.',

            'autores.required' => 'Selecione pelo menos um autor.',
            'autores.array' => 'Os autores devem ser informados corretamente.',
            'autores.min' => 'Selecione pelo menos um autor.',
            'autores.*.required' => 'O autor é obrigatório.',
            'autores.*.integer' => 'O autor selecionado é inválido.',
            'autores.*.distinct' => 'Não é permitido selecionar o mesmo autor mais de uma vez.',
            'autores.*.exists' => 'Um dos autores selecionados não existe.',

            'assuntos.required' => 'Selecione pelo menos um assunto.',
            'assuntos.array' => 'Os assuntos devem ser informados corretamente.',
            'assuntos.min' => 'Selecione pelo menos um assunto.',
            'assuntos.*.required' => 'O assunto é obrigatório.',
            'assuntos.*.integer' => 'O assunto selecionado é inválido.',
            'assuntos.*.distinct' => 'Não é permitido selecionar o mesmo assunto mais de uma vez.',
            'assuntos.*.exists' => 'Um dos assuntos selecionados não existe.',
        ];
    }
}
