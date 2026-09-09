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
			'whereLikeInsensitive' => function($column, $value){
				$driver = $this->getConnection()->getDriverName();

				switch($driver){
					case 'sqlsrv':
						return $this->whereRaw("{$column} COLLATE Latin1_General_CI_AI LIKE ?", [$value]);
					case 'mysql':
						return $this->whereRaw("{$column} LIKE ? COLLATE utf8mb4_0900_ai_ci", [$value]);
					default:
						return $this->where($column, 'LIKE', $value);
				}
			},
		];

		foreach($macros as $macroName => $macroFunction){
			if( ! Builder::hasMacro($macroName) ){
				Builder::macro($macroName, $macroFunction);
			}
		}
	}
}