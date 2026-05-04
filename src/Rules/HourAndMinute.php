<?php

namespace AugustoMoura\LaravelToolkit\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HourAndMinute implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
		if( ! $value){
			return;
		}

		if(!preg_match('/^[0-9]{2}\:[0-9]{2}$/', $value)) {
			$fail('O(a) :attribute deve conter horas e minutos no formato HH:MM.');
            return;
        }

		list($hours, $minutes) = explode(':', $value);

		$isValid = intval($hours) >= 0 && intval($hours) <= 23
			&& intval($minutes) >= 0 && intval($minutes) <= 59;
            
        if (!$isValid) {
            $fail('O(a) :attribute deve conter horas e minutos no formato HH:MM.');
        }
    }
}
