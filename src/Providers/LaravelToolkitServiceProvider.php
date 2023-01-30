<?php
namespace AugustoMoura\LaravelToolkit\Providers;

use AugustoMoura\LaravelToolkit\Macros\CollectionMacros;
use AugustoMoura\LaravelToolkit\Macros\QueryBuilderMacros;
use AugustoMoura\LaravelToolkit\Macros\StringMacros;
use AugustoMoura\LaravelToolkit\Macros\TestResponseMacros;
use Illuminate\Support\ServiceProvider;

class LaravelToolkitServiceProvider extends ServiceProvider
{
	public function register()
	{
		CollectionMacros::registerMacros();
		QueryBuilderMacros::registerMacros();
		TestResponseMacros::registerMacros();
		StringMacros::registerMacros();
	}
}