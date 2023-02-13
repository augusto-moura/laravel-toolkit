<?php

namespace AugustoMoura\LaravelToolkit\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Validates a CEP (Brazilian zip code).
 */
class Cep implements Rule
{
    public function passes($attribute, $value)
    {
		$cep = trim($value);
		return preg_match("/^[0-9]{5}-[0-9]{3}$/", $cep);
    }

    public function message()
    {
        return 'O campo :attribute deve ser um CEP válido (XXXXX-XXX).';
    }
}
