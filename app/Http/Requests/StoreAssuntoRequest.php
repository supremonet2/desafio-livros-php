<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAssuntoRequest extends FormRequest
{
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
            'Descricao' => ['required', 'string', 'max:20'],
            'CodAs' => ['required', 'integer', 'min:1', 'unique:assuntos,CodAs'],
        ];
    }

    public function messages(): array
    {
        return [
            'Descricao.required' => 'Informe a Descrição do Assunto.',
            'Descricao.max' => 'O assunto deve ter no máximo 20 caracteres.',
            'CodAs.required' => 'Informe o Código do Assunto.',
            'CodAs.unique' => 'Este código já está em uso por outro Assunto.',
        ];
    }
}
