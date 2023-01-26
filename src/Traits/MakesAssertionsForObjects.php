<?php

namespace AugustoMoura\LaravelToolkit\Traits;

trait MakesAssertionsForObjects 
{
	private function assertObjectHasAttributes($actualObj, array $expectedAttributes)
	{
		foreach($expectedAttributes as $attName => $expectedValue){
			$this->assertSame(
				$expectedValue, 
				$actualObj->$attName, 
				"Objeto deveria conter o valor {$expectedValue} no atributo {$attName}, mas contém o valor {$actualObj->$attName}."
			);
		}
	}
}