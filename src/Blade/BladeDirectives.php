<?php

namespace AugustoMoura\LaravelToolkit\Blade;

class BladeDirectives
{
	public static function registerBladeDirectives() : void
	{
		\Illuminate\Support\Facades\Blade::directive('can', function ($expression) {
            return BladeDirectives::removeExcessSpaces("<?php \$policyResponse = app(\Illuminate\\Contracts\\Auth\\Access\\Gate::class)->inspect({$expression});
			\$policyMessage = \$policyResponse->message();
			\$policyAllowed = \$policyResponse->allowed();
			if(\$policyResponse->allowed()): ?>");
        });

		\Illuminate\Support\Facades\Blade::directive('cannot', function ($expression) {
            return BladeDirectives::removeExcessSpaces("<?php \$policyResponse = app(\Illuminate\\Contracts\\Auth\\Access\\Gate::class)->inspect({$expression});
			\$policyMessage = \$policyResponse->message();
			\$policyAllowed = \$policyResponse->allowed();
			if(\$policyResponse->denied()): ?>");
        });
	}

	public static function removeExcessSpaces(string $subject)
	{
		return preg_replace('/\s+/', ' ', $subject);
	}
}