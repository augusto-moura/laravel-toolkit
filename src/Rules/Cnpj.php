<?php
namespace AugustoMoura\LaravelToolkit\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Cnpj implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
		if( ! $value){
			return;
		}

		$cnpj = preg_replace('/[^0-9]/', '', (string) $value);

		if (strlen($cnpj) != 14) {
			$fail('O campo :attribute deve conter um CNPJ válido.');
			return;
		}

		if (preg_match('/(\d)\1{13}/', $cnpj)) {
			$fail('O campo :attribute deve conter um CNPJ válido.');
			return;
		}

		for ($t = 12; $t < 14; $t++) {
			for ($d = 0, $p = $t - 7, $c = 0; $c < $t; $c++) {
				$d += $cnpj[$c] * $p;
				$p = ($p == 2) ? 9 : --$p;
			}
			$d = ((10 * $d) % 11) % 10;
			if ($cnpj[$c] != $d) {
				$fail('O campo :attribute deve conter um CNPJ válido.');
				return;
			}
		}
    }
}