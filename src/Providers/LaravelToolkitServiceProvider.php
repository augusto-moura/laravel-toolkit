<?php
namespace AugustoMoura\LaravelToolkit\Providers;

use AugustoMoura\LaravelToolkit\Macros\CollectionMacros;
use AugustoMoura\LaravelToolkit\Macros\QueryBuilderMacros;
use Illuminate\Support\ServiceProvider;

class LaravelToolkitServiceProvider extends ServiceProvider
{
	public function register()
	{
		CollectionMacros::registerMacros();
		QueryBuilderMacros::registerMacros();
	}
}