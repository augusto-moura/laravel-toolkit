<?php

namespace AugustoMoura\LaravelToolkit\Macros;

use Illuminate\Database\Query\Builder;

class QueryBuilderMacros
{
	public static function registerMacros() : void
	{
		$macros = [
			'whereAny' => function(array $conditionsArray){
				return $this->where(function($query) use($conditionsArray){
					foreach($conditionsArray as $conditionFunction){
						$query->orWhere($conditionFunction);
					}
				});
			},
			'whereNot' => function($column, $value = null){
				return $this->where($column, $value, null, 'and not');
			},
		];

		foreach($macros as $macroName => $macroFunction){
			if( ! Builder::hasMacro($macroName) ){
				Builder::macro($macroName, $macroFunction);
			}
		}
	}
}