<?php

namespace App\Http\Requests;

class UpdateLivroRequest extends StoreLivroRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        unset($rules['Codl']);

        return $rules;
    }
}
