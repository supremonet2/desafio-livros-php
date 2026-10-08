<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAutorRequest extends FormRequest
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
            'Nome' => ['required', 'string', 'max:40'],
            'CodAu' => ['required', 'integer', 'min:1', 'unique:autors,CodAu'],
        ];
    }

    public function messages(): array
    {
        return [
            'Nome.required' => 'Informe o Nome do Autor.',
            'Nome.max' => 'O nome deve ter no máximo 40 caracteres.',
            'CodAu.required' => 'Informe o Código do Autor.',
            'CodAu.unique' => 'Este código de autor já está em uso.',
        ];
    }
}
