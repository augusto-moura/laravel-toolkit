<?php

namespace AugustoMoura\LaravelToolkit\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates a CEP (Brazilian zip code).
 */
class Cep implements ValidationRule
{
	function __construct(
		public bool $exigirSeparador = true
	)
	{
		//
	}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
		if( ! $value){
			return;
		}

		$cep = str($value)->trim()
			->when( ! $this->exigirSeparador, fn($str) =>
				$str->replaceMatches('/\D/', '')
			)
			->toString()
		;

		$isValid = $this->exigirSeparador ?
			preg_match("/^[0-9]{5}-[0-9]{3}$/", $cep) :
			preg_match("/^[0-9]{8}$/", $cep)
		;

        if (!$isValid) {
            $fail('O campo :attribute deve ser um CEP válido (XXXXX-XXX).');
        }
    }
}
