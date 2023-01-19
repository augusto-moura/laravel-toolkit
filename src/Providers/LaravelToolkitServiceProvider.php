<?php
namespace AugustoMoura\LaravelToolkit\Providers;

use AugustoMoura\LaravelToolkit\Macros\CollectionMacros;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class LaravelToolkitServiceProvider extends ServiceProvider
{
	public function register()
	{
		CollectionMacros::registerMacros();
	}
}