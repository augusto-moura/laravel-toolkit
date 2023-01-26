<?php

namespace AugustoMoura\LaravelToolkit\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Validates a CPF (Brazilian equivalent of the Social Security Number).
 */
class Cpf implements Rule
{
    public function passes($attribute, $value)
    {
		// If it's empty, returns false. , fills with zeroes to the left up to 11 digits, checks if the number of digits is equal to 11. Checks if any of the invalid sequences below has been entered. If so, returns false. Otherwise, calculates the verification digits to check if the CPF is valid and returns true
		
        // Checks if a number has been entered.
		if(empty($value)) {
			return false;
		}

		// Remove possible mask
		$value = preg_replace("/[^0-9]/", "", $value);
		$value = str_pad($value, 11, '0', STR_PAD_LEFT);
		
		// checks if the number of digits is equal to 11 
		if (strlen($value) != 11) {
			return false;
		}

		// check if digits are not all the same
		$isValueInBlacklist = in_array($value, [
			'00000000000',
			'11111111111',
			'22222222222',
			'33333333333',
			'44444444444',
			'55555555555',
			'66666666666',
			'77777777777',
			'88888888888',
			'99999999999',
		]);
		if ($isValueInBlacklist) {
			return false;
		}

		// Calculate the verification digits
		for ($t = 9; $t < 11; $t++) {
			for ($d = 0, $c = 0; $c < $t; $c++) {
				$d += $value[$c] * (($t + 1) - $c);
			}
			$d = ((10 * $d) % 11) % 10;
			if ($value[$c] != $d) {
				return false;
			}
		}

		return true;
    }

    public function message()
    {
        return 'O campo :attribute deve conter um CPF válido.';
    }
}
