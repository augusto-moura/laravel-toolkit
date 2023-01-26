<?php

namespace AugustoMoura\LaravelToolkit\Rules;

use Illuminate\Contracts\Validation\Rule;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class MaxWordsInHtml implements Rule
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
		$text = $element->text();
		$wordCount = self::countWords($text);
		return $wordCount <= $this->max;
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

    public function message()
    {
        return "O campo :attribute não pode conter mais de {$this->max} palavras.";
    }
}
