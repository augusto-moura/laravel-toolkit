<?php

namespace AugustoMoura\LaravelToolkit\Rules;

use Illuminate\Contracts\Validation\Rule;

class HourAndMinute implements Rule
{
    public function passes($attribute, $value)
    {
		if(!preg_match('/^[0-9]{2}\:[0-9]{2}$/', $value))
			return false;

		list($hours, $minutes) = explode(':', $value);

		return intval($hours) >= 0 && intval($hours) <= 23
			&& intval($minutes) >= 0 && intval($minutes) <= 59;
    }

    public function message()
    {
        return 'O(a) :attribute deve conter horas e minutos no formato HH:MM.';
    }
}
