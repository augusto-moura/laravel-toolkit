<?php

namespace AugustoMoura\LaravelToolkit\Traits;

use Illuminate\Contracts\Validation\Rule;

trait MakesAssertionsForValidationRules 
{
	private function assertValidationRule(Rule $rule, $value, bool $shouldPass = true)
	{
		if($shouldPass){
			$this->assertTrue(
				!! $rule->passes('attribute_name', $value),
				"Value '{$value}' should have passed the " . get_class($rule) . " rule, but didn't."
			);
		}
		else{
			$this->assertFalse(
				!! $rule->passes('attribute_name', $value),
				"Value '{$value}' should have failed the " . get_class($rule) . " rule, but it didn't."
			);
		}
	}

	/**
	 * @param Rule $rule
	 * @param array<mixed,bool> $inputsAndResults
	 */
	private function assertValidationRuleForMultipleValues(Rule $rule, array $inputsAndResults)
	{
		foreach($inputsAndResults as $input => $result){
			$this->assertValidationRule($rule, $input, $result);
		}
	}
}