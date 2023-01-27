<?php

use AugustoMoura\LaravelToolkit\Providers\LaravelToolkitServiceProvider;
use Illuminate\Testing\TestResponse;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\ExpectationFailedException;
use Symfony\Component\HttpFoundation\Response;

class TestResponseMacrosTest extends TestCase
{
	protected function getPackageProviders($app)
    {
        return [LaravelToolkitServiceProvider::class];
    }

    public function test_assert_content_html_matches_selector()
    {
		$testResponse = $this->getTestResponse();
		$testResponse->assertContentHtmlMatchesSelector('.header')
			->assertContentHtmlMatchesSelector('input[name=login]', true);

		$this->expectException(ExpectationFailedException::class);
		$testResponse->assertContentHtmlMatchesSelector('.nonexistant');
    }

    public function test_assert_content_html_doesnt_match_selector()
    {
		$testResponse = $this->getTestResponse();
		$testResponse->assertContentHtmlMatchesSelector('select', false);

		$this->expectException(ExpectationFailedException::class);
		$testResponse->assertContentHtmlMatchesSelector('input[name=login]', false);
    }

	private function getTestResponse() : TestResponse
	{
		$html = $this->getTestHtml();
		$response = new Response($html);
		return new TestResponse($response);
	}

	private function getTestHtml() : string
	{
		ob_start();
		?><html>
			<body>
				<h1 class="header">Hello World</h1>
				<input name="login" />
			</body>
		</html>
		<?php
		return ob_get_clean();
	}
}