<?php

namespace AugustoMoura\LaravelToolkit\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class MaxCharactersInHtml implements ValidationRule
{
	private int $max;
	
	public function __construct($max)
	{
		$this->max = (int) $max;
	}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
		if ($value === null || $value === '') {
            return;
        }

		//add divs for manipulation
		$element = HtmlPageCrawler::create("<div>{$value}</div>");
		$texto = $element->text();
		$characterCount = strlen($texto);
		
        if ($characterCount > $this->max) {
            $fail("O campo :attribute não pode conter mais de {$this->max} caracteres.");
        }
    }
}
