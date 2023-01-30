<?php

namespace AugustoMoura\LaravelToolkit\Macros;

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

class StringMacros
{
	public static function registerMacros() : void
	{
		$macros = [
			'titleWithSpaces' => function($string){
				return (string) Str::of($string)
					->kebab()
					->replace('-', ' ')
					->title();
			},
			'superTrim' => function($string){
				return trim($string, " \t\n\r\0\x0B\xC2\xA0");
			},
		];

		foreach($macros as $macroName => $macroFunction){
			if( ! Str::hasMacro($macroName) ){
				Str::macro($macroName, $macroFunction);
			}

			if( ! Stringable::hasMacro($macroName) ){
				Stringable::macro(
					$macroName, 
					function() use($macroFunction){
						return Str::of($macroFunction($this->value));
					}
				);
			}
		}
	}
}