<?php

use AugustoMoura\LaravelToolkit\Providers\LaravelToolkitServiceProvider;
use Orchestra\Testbench\TestCase;

class CollectionMacrosTest extends TestCase
{
	protected function getPackageProviders($app)
    {
        return [LaravelToolkitServiceProvider::class];
    }

    public function test_map_to_integer()
    {
        $collection = collect(["1", "55", "-7", null]);
		$this->assertEquals(
			[1, 55, -7, (int)null],
			$collection->mapToInteger()->toArray()
		);

		$collection = collect([
			'id' => '1',
			'quantity' => '12',
			'max' => '30',
		]);
		$this->assertEquals(
			[
				'id' => 1,
				'quantity' => 12,
				'max' => 30,
			],
			$collection->mapToInteger()->toArray()
		);
    }

    public function test_remove_string_from_keys()
    {
		$collection = collect([
			'id' => 1,
			'##name##' => '#keep#',
			'#before' => 'value',
			'after#' => 'other',
		]);
		$this->assertEquals(
			[
				'id' => 1,
				'name' => '#keep#',
				'before' => 'value',
				'after' => 'other',
			],
			$collection->removeStringFromKeys('#')->toArray()
		);
    }

    public function test_trim_strings()
    {
		$collection = collect([' a ', ' a', 'a ', 'a', ' abc def ', 100, [1,2,3]]);
		$this->assertEquals(
			['a', 'a', 'a', 'a', 'abc def', 100, [1,2,3]],
			$collection->trimStrings()->toArray()
		);
    }

	public function test_map_alternating_key_and_value()
    {
		$collection = collect([
			'a' => 'b',
			'c' => 'd',
		]);
		$this->assertEquals(
			['a', 'b', 'c', 'd'],
			$collection->mapAlternatingKeyAndValue()->toArray()
		);
    }

	public function test_contains_all()
    {
		$collection = collect([1, 2, 3, 4]);
		$this->assertTrue($collection->containsAll([]));
		$this->assertTrue($collection->containsAll([1, 2, 3]));
		$this->assertTrue($collection->containsAll([1, 2, 3, 4]));
		$this->assertFalse($collection->containsAll([1, 2, 3, 4, 5]));
		$this->assertFalse($collection->containsAll([1, 2, 3, 10]));
		$this->assertFalse($collection->containsAll([1, -1, 2, 3]));
    }
}