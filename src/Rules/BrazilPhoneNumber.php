<?php

namespace AugustoMoura\LaravelToolkit\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BrazilPhoneNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
		if( ! $value){
			return;
		}

        if (!preg_match('/^(\+55|55)?\s?(((0)?[0-9]{2})|(\((0)?[0-9]{2}\)))\s?([1-9]{1})?\s?[0-9]{4}[\s\-]?[0-9]{4}$/', $value)) {
            $fail('O campo :attribute precisa conter um número de telefone válido. Ex.: 061 91234 1234');
        }
    }
}
