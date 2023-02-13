<?php

use AugustoMoura\LaravelToolkit\Providers\LaravelToolkitServiceProvider;
use Orchestra\Testbench\TestCase;

class BladeDirectivesTest extends TestCase
{
	protected $blade;

	public function setUp(): void
    {
        parent::setUp();

        $this->blade = app('blade.compiler');
    }

	protected function getPackageProviders($app)
    {
        return [LaravelToolkitServiceProvider::class];
    }

    public function test_can()
    {
		$blade = "@can('edit', \$post) {{ \$policyMessage }} @endcan";

        $expected = "<?php \$policyResponse = app(\Illuminate\\Contracts\\Auth\\Access\\Gate::class)->inspect('edit', \$post); \$policyMessage = \$policyResponse->message(); \$policyAllowed = \$policyResponse->allowed(); if(\$policyResponse->allowed()): ?> <?php echo e(\$policyMessage); ?> <?php endif; ?>";

        $this->assertSame($expected, $this->blade->compileString($blade));
    }
	
    public function test_cannot()
    {
		$blade = "@cannot('edit', \$post) {{ \$policyMessage }} @endcan";

        $expected = "<?php \$policyResponse = app(\Illuminate\\Contracts\\Auth\\Access\\Gate::class)->inspect('edit', \$post); \$policyMessage = \$policyResponse->message(); \$policyAllowed = \$policyResponse->allowed(); if(\$policyResponse->denied()): ?> <?php echo e(\$policyMessage); ?> <?php endif; ?>";

        $this->assertSame($expected, $this->blade->compileString($blade));
    }
}