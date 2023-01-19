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

	public function test_prepend_keys()
    {
		$collection = collect([
			'aaa' => 1,
			'bbb' => 2,
		]);
		$this->assertEquals(
			[
				'##aaa' => 1,
				'##bbb' => 2,
			],
			$collection->prependKeys('##')->toArray()
		);

		$collection = collect(['value', 'other']);
		$this->assertEquals(
			[
				'##0' => 'value',
				'##1' => 'other',
			],
			$collection->prependKeys('##')->toArray()
		);
    }

	public function test_insert_after()
    {
		$collection = collect(['a', 'b', 'c']);
		$this->assertEquals(
			['a', 'b', 'pause', 'c'],
			$collection->insertAfter('b', 'pause')->toArray()
		);

		$collection = collect([1, 2, 3]);
		$this->assertEquals(
			[1, 2, 'pause', 3],
			$collection->insertAfter(2, 'pause')->toArray()
		);

		$collection = collect([1, 2, 2, 2, 3]);
		$this->assertEquals(
			[1, 2, 'pause', 2, 'pause', 2, 'pause', 3],
			$collection->insertAfter(2, 'pause')->toArray()
		);

		$collection = collect([1, 2, 3]);
		$this->assertEquals(
			[1, 2, 3],
			$collection->insertAfter(5, 'pause')->toArray()
		);
    }


}