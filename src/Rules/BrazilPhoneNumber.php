<?php

namespace AugustoMoura\LaravelToolkit\Rules;

use Illuminate\Contracts\Validation\Rule;

class BrazilPhoneNumber implements Rule
{
    public function passes($attribute, $value)
    {
		return preg_match('/^(\+55|55)?\s?(((0)?[0-9]{2})|(\((0)?[0-9]{2}\)))\s?([1-9]{1})?\s?[0-9]{4}\s?[0-9]{4}$/', $value);
    }

    public function message()
    {
        return 'O campo :attribute precisa conter um número de telefone válido. Ex.: 061 91234 1234';
    }
}
