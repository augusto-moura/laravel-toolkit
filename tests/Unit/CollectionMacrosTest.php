<?php

use AugustoMoura\LaravelToolkit\Providers\LaravelToolkitServiceProvider;
use Illuminate\Support\Collection;
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

	public function test_recursive()
    {
		$collection = collect([
			0 => 'a', 
			1 => 'b', 
			2 => [1,2,3]
		])
			->recursive();

		$this->assertInstanceOf(Collection::class, $collection);
		$this->assertEquals('a', $collection->get(0));
		$this->assertEquals('b', $collection->get(1));
		$this->assertInstanceOf(Collection::class, $collection->get(2));

		$subCollection = $collection->get(2);
		$this->assertEquals(
			[1, 2, 3],
			$subCollection->toArray()
		);
    }

	public function test_empty_strings_to_null()
	{
		$collection = new Collection(['foo', '', 'bar', '']);
		$expected = new Collection(['foo', null, 'bar', null]);
	
		$this->assertEquals($expected, $collection->emptyStringsToNull());
	}

	public function test_first_where_has_min()
	{
		// Test array of arrays
		$collection = new Collection([
            ['name' => 'John', 'age' => 25],
            ['name' => 'Jane', 'age' => 30],
            ['name' => 'Bob', 'age' => 25],
            ['name' => 'Joseph', 'age' => 29],
        ]);

        $expected = ['name' => 'John', 'age' => 25];
        $this->assertEquals($expected, $collection->firstWhereHasMin('age'));

		 // Test array of anonymous objects
		 $collection = new Collection([
            (object) ['name' => 'John', 'age' => 25],
            (object) ['name' => 'Jane', 'age' => 30],
            (object) ['name' => 'Bob', 'age' => 25],
            (object) ['name' => 'Joseph', 'age' => 29],
        ]);

        $expected = (object) ['name' => 'John', 'age' => 25];
        $this->assertEquals($expected, $collection->firstWhereHasMin('age'));

        // Test array of instantiated classes
		function getClassObject($name, $age){
			return new class($name, $age){
				public $name;
				public $age;
				function __construct($name, $age) {
					$this->name = $name;
					$this->age = $age;
				}
			};
		}

        $collection = new Collection([
            getClassObject('John', 25),
            getClassObject('Jane', 30),
            getClassObject('Bob', 25),
            getClassObject('Joseph', 29),
        ]);

        $expected = getClassObject('John', 25);
        $this->assertEquals($expected, $collection->firstWhereHasMin('age'));
	}

	public function test_implode_with_diff_last_separator()
	{
		$collection = new Collection([
            (object) ['name' => 'John'],
            (object) ['name' => 'Jane'],
            (object) ['name' => 'Bob'],
            (object) ['name' => 'Joseph'],
        ]);
        $expected = 'John, Jane, Bob and Joseph';
        $this->assertEquals($expected, $collection->implodeWithDiffLastSeparator('name', [', ', ' and ']));

		$collection = new Collection(['John', 'Jane', 'Bob', 'Joseph']);
        $expected = 'John, Jane, Bob and Joseph';
        $this->assertEquals($expected, $collection->implodeWithDiffLastSeparator([', ', ' and ']));
	}

}