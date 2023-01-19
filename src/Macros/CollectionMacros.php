<?php

namespace AugustoMoura\LaravelToolkit\Macros;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CollectionMacros
{
	public static function registerMacros() : void
	{
		$macros = [
			'mapToInteger' => function(){
				/** @var Collection $this */
				return $this->map(function($value){
					return (int) $value;
				});
			},

			'removeStringFromKeys' => function(string $string){
				/** @var Collection $this */
				return $this->mapWithKeys(function($item, $key) use($string){
					$newKey = (string)\Illuminate\Support\Str::of($key)->replace($string, '');
					return [$newKey => $item];
				});
			},

			'trimStrings' => function(){
				/** @var Collection $this */
				return $this->map(function($item){
					if(!is_string($item))
						return $item;

					return function_exists(Str::class . '::superTrim') ? 
						Str::superTrim($item) :
						trim($item);
				});
			},

			/**
			 * Ex.: [a => b, c => d] ----> [a,b,c,d]
			 */
			'mapAlternatingKeyAndValue' => function(){
				/** @var Collection $this */
				return $this
					->map(function($value, $key){
						return collect([$key, $value]);
					})
					->flatten();
			},
			
			'containsAll' => function($elements){
				/** @var Collection $this */
				$collection =& $this;

				return Collection::wrap($elements)
					->every(function($element) use($collection){
						return $collection->contains($element);
					});
			},
		];

		foreach($macros as $macroName => $macroFunction){
			if( ! Collection::hasMacro($macroName) ){
				Collection::macro($macroName, $macroFunction);
			}
		}
	}
}