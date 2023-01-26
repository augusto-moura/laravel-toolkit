<?php

namespace AugustoMoura\LaravelToolkit\Rules;

use Illuminate\Contracts\Validation\Rule;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class MaxCharactersInHtml implements Rule
{
	private $max;
	
	public function __construct($max)
	{
		$this->max = (int) $max;
	}

    public function passes($attribute, $value)
    {
		//add divs for manipulation
		$element = HtmlPageCrawler::create("<div>{$value}</div>");
		$texto = $element->text();
		$characterCount = strlen($texto);
		return $characterCount <= $this->max;
    }

    public function message()
    {
        return "O campo :attribute não pode conter mais de {$this->max} caracteres.";
    }
}
