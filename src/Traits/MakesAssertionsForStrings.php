<?php

namespace AugustoMoura\LaravelToolkit\Traits;
use Illuminate\Testing\Assert as PHPUnit;

trait MakesAssertionsForStrings
{
	private function assertEqualsNormalizingSpaces(string $expected, string $actual)
	{
		PHPUnit::assertEquals(
			preg_replace('/[\s\t\n]{2,}/', ' ', $expected),
			preg_replace('/[\s\t\n]{2,}/', ' ', $actual),
			"Failed to assert that the strings are equal considering multiple spaces as one."
		);
	}
}