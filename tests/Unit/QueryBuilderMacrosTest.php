<?php

use AugustoMoura\LaravelToolkit\Providers\LaravelToolkitServiceProvider;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Processors\Processor;
use Orchestra\Testbench\TestCase;

class QueryBuilderMacrosTest extends TestCase
{
	protected function getPackageProviders($app)
    {
        return [LaravelToolkitServiceProvider::class];
    }

    public function test_where_any()
    {
		$builder = $this->getBuilder();

		$builder->whereAny([
			function($q){
				$q->whereRaw('true');
			},
			function($q){
				$q->whereRaw('false');
			},
			function($q){
				$q->whereRaw('false and false');
			}
		]);

		$this->assertSame(
			'select * where ((true) or (false) or (false and false))', 
			strtolower($builder->toSql())
		);
    }

    public function test_where_not()
    {
        $builder = $this->getBuilder();

		$builder->whereNot(function($q){
			$q->whereRaw('true');
		});

		$this->assertSame(
			'select * where not (true)', 
			strtolower($builder->toSql())
		);
    }

	protected function getBuilder()
    {
        $grammar = new Grammar;
        $processor = Mockery::mock(Processor::class);

        return new Builder($this->getConnection(), $grammar, $processor);
    }
}