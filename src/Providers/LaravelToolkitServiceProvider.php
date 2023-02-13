<?php
namespace AugustoMoura\LaravelToolkit\Providers;

use AugustoMoura\LaravelToolkit\Blade\BladeDirectives;
use AugustoMoura\LaravelToolkit\Macros\CollectionMacros;
use AugustoMoura\LaravelToolkit\Macros\QueryBuilderMacros;
use AugustoMoura\LaravelToolkit\Macros\StringMacros;
use AugustoMoura\LaravelToolkit\Macros\TestResponseMacros;
use Illuminate\Support\ServiceProvider;

class LaravelToolkitServiceProvider extends ServiceProvider
{
	public function boot()
	{
		$this->loadViewsFrom(__DIR__.'/../../resources/views', 'am-laravel-toolkit');
	}

	public function register()
	{
		CollectionMacros::registerMacros();
		QueryBuilderMacros::registerMacros();
		TestResponseMacros::registerMacros();
		StringMacros::registerMacros();

		BladeDirectives::registerBladeDirectives();
	}
}