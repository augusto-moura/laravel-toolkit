<?php

namespace AugustoMoura\LaravelToolkit\Traits;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;

trait MakesAssertionsForValidationRules 
{
	protected function assertValidationRule(Rule $rule, $value, bool $shouldPass = true)
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

	protected function assertLaravel11ValidationRule(ValidationRule $rule, $value, bool $shouldPass = true)
	{
		$passed = true;
		$rule->validate(
			'attribute_name',
			$value,
			function() use(&$passed){
				$passed = false;
			}
		);

		if($shouldPass){
			$this->assertTrue(
				$passed,
				"Value '{$value}' should have passed the " . get_class($rule) . " rule, but didn't."
			);
		}
		else{
			$this->assertFalse(
				$passed,
				"Value '{$value}' should have failed the " . get_class($rule) . " rule, but it didn't."
			);
		}
	}

	/**
	 * @param Rule $rule
	 * @param array<mixed,bool> $inputsAndResults
	 */
	protected function assertValidationRuleForMultipleValues(Rule|ValidationRule $rule, array $inputsAndResults)
	{
		foreach($inputsAndResults as $input => $result){
			$treatedInput = is_numeric($input) ? (string) $input : $input;

			if($rule instanceof Rule){
				$this->assertValidationRule($rule, $treatedInput, $result);
			}
			if($rule instanceof ValidationRule){
				$this->assertLaravel11ValidationRule($rule, $treatedInput, $result);
			}
		}
	}
}