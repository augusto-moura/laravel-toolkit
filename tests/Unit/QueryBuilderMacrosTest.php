<?php

use AugustoMoura\LaravelToolkit\Providers\LaravelToolkitServiceProvider;
use Illuminate\Database\Connection;
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

    public function test_where_like_insensitive_default_driver()
    {
		$builder = $this->getBuilderWithDriver('sqlite');
		$result = $builder->from('users')->whereLikeInsensitive('name', '%john%');

		$this->assertSame($builder, $result);
		$this->assertSame(
			'select * from "users" where "name" like ?',
			strtolower($builder->toSql())
		);
		$this->assertSame(['%john%'], $builder->getBindings());
    }

    public function test_where_like_insensitive_does_not_add_wildcards()
    {
		$builder = $this->getBuilderWithDriver('pgsql');
		$builder->from('users')->whereLikeInsensitive('name', 'john');
		$this->assertSame(['john'], $builder->getBindings());
    }

    public function test_where_like_insensitive_uses_value_verbatim()
    {
		$builder = $this->getBuilderWithDriver('sqlite');
		$builder->from('users')->whereLikeInsensitive('name', '%john%');
		$this->assertSame(['%john%'], $builder->getBindings());
		$builder = $this->getBuilderWithDriver('sqlite');
		$builder->from('users')->whereLikeInsensitive('name', 'john%');
		$this->assertSame(['john%'], $builder->getBindings());
    }

    public function test_where_like_insensitive_mysql()
    {
		$builder = $this->getBuilderWithDriver('mysql');
		$builder->from('users')->whereLikeInsensitive('name', '%john%');
		$this->assertSame(
			'select * from "users" where name like ? collate utf8mb4_0900_ai_ci',
			strtolower($builder->toSql())
		);
		$this->assertSame(['%john%'], $builder->getBindings());
    }

    public function test_where_like_insensitive_sqlsrv()
    {
		$builder = $this->getBuilderWithDriver('sqlsrv');
		$builder->from('users')->whereLikeInsensitive('name', '%john%');
		$this->assertSame(
			'select * from "users" where name collate latin1_general_ci_ai like ?',
			strtolower($builder->toSql())
		);
		$this->assertSame(['%john%'], $builder->getBindings());
    }

	protected function getBuilder()
    {
        $grammar = new Grammar;
        $processor = Mockery::mock(Processor::class);
        return new Builder($this->getConnection(), $grammar, $processor);
    }

	protected function getBuilderWithDriver(string $driver)
    {
        $connection = Mockery::mock(Connection::class);
        $connection->shouldReceive('getDriverName')->andReturn($driver);
        $grammar = new Grammar;
        $processor = Mockery::mock(Processor::class);
        return new Builder($connection, $grammar, $processor);
    }
}