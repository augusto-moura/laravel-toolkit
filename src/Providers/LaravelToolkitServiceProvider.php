<?php
namespace AugustoMoura\LaravelToolkit\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class LaravelToolkitServiceProvider extends ServiceProvider
{
	/**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
	}
	
	public function register()
	{
		Collection::macro('mapToInteger', function(){
			/** @var Collection $this */
			return $this->map(function($value){
				return (int) $value;
			});
		});
	}
}