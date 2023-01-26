<?php

namespace AugustoMoura\LaravelToolkit\Rules;

use Illuminate\Contracts\Validation\Rule;

class HtmlNotEmpty implements Rule
{
    public function passes($attribute, $value)
    {
		if($value === null)
			return false;

		$value = strtr($value, [
			'<br>' => '',
			'<br/>' => '',
			'<br />' => ''
		]);
		
		return trim(strip_tags($value)) != '';
    }

    public function message()
    {
        return 'O texto não pode estar vazio ou conter apenas espaços em branco.';
    }
}
