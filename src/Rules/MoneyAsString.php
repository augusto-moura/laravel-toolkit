<?php
namespace AugustoMoura\LaravelToolkit\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MoneyAsString implements ValidationRule
{
	function __construct(
		public string $thousandSeparator = '.',
		public string $decimalSeparator = ',',
	)
	{
		//
	}

	public function withThousandSeparator(string $thousandSeparator) : self
	{
		$this->thousandSeparator = $thousandSeparator;
		return $this;
	}

	public function withDecimalSeparator(string $decimalSeparator) : self
	{
		$this->decimalSeparator = $decimalSeparator;
		return $this;
	}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
		if( ! $value){
			return;
		}

		$thousandEscaped = '\\' . $this->thousandSeparator;
		$decimalEscaped = '\\' . $this->decimalSeparator;
		$regex = "/^(\d{1,3}|(\d{1,3}{$thousandEscaped})+\d{3}){$decimalEscaped}\d{2}$/";

		preg_match($regex, $value, $matches);
		$isStringValid = isset($matches[0]);
		
        if ( ! $isStringValid) {
            $fail("The number must follow the following format: 123{$this->thousandSeparator}456{$this->decimalSeparator}78 .");
        }
    }
}