<?php

namespace App\Http\Requests;

class UpdateAssuntoRequest extends StoreAssuntoRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        unset($rules['CodAs']);

        return $rules;
    }
}
