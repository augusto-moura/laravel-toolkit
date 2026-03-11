<?php

namespace AugustoMoura\LaravelToolkit\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Regra de validação que será aplicada mesmo quando o campo estiver vazio.
 */
class Implicit implements ValidationRule
{
    public $implicit = true;

    function __construct(
		public Closure $regra,
	)
    {
        //
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        ($this->regra)($attribute, $value, $fail);
    }
}
