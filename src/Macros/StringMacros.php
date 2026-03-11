<?php

namespace AugustoMoura\LaravelToolkit\Macros;

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

class StringMacros
{
	public static function registerMacros() : void
	{
		$macrosWithNoParameter = [
			'titleWithSpaces' => function($string){
				return (string) Str::of($string)
					->kebab()
					->replace('-', ' ')
					->title();
			},

			//Make the first letter of each word capitalized, but preserve words like 'of','and', etc.
			'capitalizedName' => function($string){
				return (string) Str::of($string)
					->explode(' ')
					->map(function($word){
						$wordTitleCase = (string)str($word)
							->title()
							->trim()
						;
						return match($wordTitleCase){
							'De' => 'de',
							'Da' => 'da',
							'Do' => 'do',
							'Di' => 'di',
							'Das' => 'das',
							'Dos' => 'dos',
							'E' => 'e',
							'Com' => 'com',
							'Em' => 'em',
							'No' => 'no',
							'Na' => 'na',
							'Nos' => 'nos',
							'Nas' => 'nas',
							'A' => 'a',
							'O' => 'o',
							'The' => 'the',
							'Of' => 'of',
							'And' => 'and',
							'Or' => 'or',
							default => $wordTitleCase,
						};
					})
					->join(' ')
				;
			},

			'superTrim' => function($string){
				return trim($string, " \t\n\r\0\x0B\xC2\xA0");
			},

			'removeExcessWhitespaces' => function($string){
				return preg_replace('/\s+/', ' ', $string);
			},
		];

		foreach($macrosWithNoParameter as $macroName => $macroFunction){
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

		$wordWrapFunction = function(string $input, int $charLimitPerLine){

			$str = Str::of($input);

			if($str->length() <= $charLimitPerLine)
				return [(string)$str];

			$palavras = $str->split('/\s+/');

			$parts = [];
			$currentLine = 0;

			foreach($palavras as $palavra){
				if(strlen($palavra) > $charLimitPerLine)
					throw new \LengthException("Uma das palavras é maior que o limite de {$charLimitPerLine} caracteres por linha: {$palavra}.");

				do{
					$parts[$currentLine] = $parts[$currentLine] ?? '';
					$currentLineText = $parts[$currentLine];

					$currentLinePlusSpaceIfNotEmpty = (
						$currentLineText == '' ? 
						'' : 
						"{$currentLineText} "
					);
					
					if(Str::length($currentLinePlusSpaceIfNotEmpty) + Str::length($palavra) <= $charLimitPerLine){
						$parts[$currentLine] = "{$currentLinePlusSpaceIfNotEmpty}{$palavra}";
						break;
					}
					$currentLine++;
				}
				while($currentLine < 100);

				continue;
			}

			return $parts;
		};

		$macroName = 'wordWrapWithoutBreakingWords';
		if( ! Str::hasMacro($macroName) ){
			Str::macro($macroName, $wordWrapFunction);
		}

		if( ! Stringable::hasMacro($macroName) ){
			Stringable::macro(
				$macroName, 
				function(int $charLimitPerLine) use($wordWrapFunction){
					return $wordWrapFunction($this->value, $charLimitPerLine);
				}
			);
		}
	}
}