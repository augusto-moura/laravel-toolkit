<?php

namespace AugustoMoura\LaravelToolkit\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Validates a CEP (Brazilian zip code).
 */
class Cep implements Rule
{
	function __construct(
		public bool $exigirSeparador = true
	)
	{
		//
	}

    public function passes($attribute, $value)
    {
		$cep = str($value)->trim()
			->when( ! $this->exigirSeparador, fn($str) =>
				$str->replaceMatches('/\D/', '')
			)
			->toString()
		;
		return
			$this->exigirSeparador ?
			preg_match("/^[0-9]{5}-[0-9]{3}$/", $cep) :
			preg_match("/^[0-9]{8}$/", $cep)
		;
    }

    public function message()
    {
        return 'O campo :attribute deve ser um CEP válido (XXXXX-XXX).';
    }
}
