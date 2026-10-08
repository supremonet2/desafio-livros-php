<?php

namespace App\Http\Requests;


class UpdateAutorRequest extends StoreAutorRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        unset($rules['CodAu']);

        return $rules;
    }
}
