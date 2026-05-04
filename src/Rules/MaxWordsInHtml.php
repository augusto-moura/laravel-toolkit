<?php

namespace AugustoMoura\LaravelToolkit\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class MaxWordsInHtml implements ValidationRule
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
		$text = $element->text();
		$wordCount = self::countWords($text);

		if ($wordCount > $this->max) {
            $fail("O campo :attribute não pode conter mais de {$this->max} palavras.");
        }
    }

	public static function countWords(string $text) : int
	{
		$words = collect(explode(' ', trim($text)));
		return $words
			->filter(function($word){
				//consider as word only if contains at least one of the following characters
				return preg_match('/[A-Za-z0-9áéíóúãõâêôçà]+/', $word);
			})
			->count()
		;
	}
}
