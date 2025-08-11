<?php
namespace AugustoMoura\LaravelToolkit\Helpers;

use Illuminate\Support\Collection;

class LaravelToolkit
{
	public static function exportVar($objectOrArray) : string
	{
		if($objectOrArray instanceof Collection){
			$objectOrArray = $objectOrArray->toArray();
		}
		$asArray = (array) $objectOrArray;

		$isArrayAssoc = self::isArrayAssoc($asArray);
		$asString = collect($asArray)
			->filter()
			->map(function($value, $key) use($isArrayAssoc){
				$isValueIterable = is_iterable($value);
				$valueAsString = (
					$isValueIterable ?
					self::exportVar($value) :
					"\"{$value}\""
				);
				return $isArrayAssoc ? 
					"{$key}: {$valueAsString}" :
					"{$valueAsString}"
				;
			})
			->implode(' | ');

		return "[{$asString}]";
	}

	private static function isArrayAssoc(array $array) : bool
	{
		return count(array_filter(array_keys($array), 'is_string')) > 0;
	}
}