<?php

use AugustoMoura\LaravelToolkit\Providers\LaravelToolkitServiceProvider;
use Orchestra\Testbench\TestCase;

class CollectionMacrosTest extends TestCase
{
	protected function getPackageProviders($app)
    {
        return [LaravelToolkitServiceProvider::class];
    }

    /** @test */
    public function test_map_to_integer()
    {
        $collection = collect(["1", "55", "-7", null]);
		$this->assertEquals(
			collect([1, 55, -7, (int)null])->toArray(),
			$collection->mapToInteger()->toArray()
		);
    }
}