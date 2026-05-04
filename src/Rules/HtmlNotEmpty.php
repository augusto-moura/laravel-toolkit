<?php

namespace AugustoMoura\LaravelToolkit\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HtmlNotEmpty implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
		if($value === null) {
			$fail('O texto não pode estar vazio ou conter apenas espaços em branco.');
            return;
        }

		$value = strtr($value, [
			'<br>' => '',
			'<br/>' => '',
			'<br />' => ''
		]);
		
		if (trim(strip_tags($value)) == '') {
            $fail('O texto não pode estar vazio ou conter apenas espaços em branco.');
        }
    }
}
