<?php

namespace AugustoMoura\LaravelToolkit\Macros;

use Illuminate\Testing\TestResponse;
use Illuminate\Testing\Assert as PHPUnit;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class TestResponseMacros
{
	public static function registerMacros() : void
	{
		$macros = [
			'assertContentHtmlMatchesSelector' => function(string $selector, bool $shouldMatch = true){
				/** @var \Illuminate\Testing\TestResponse $this */
				$html = (string)$this->baseResponse->getContent();
				$crawler = HtmlPageCrawler::create($html);
				$count = $crawler->filter($selector)->count();
				
				if($shouldMatch){
					PHPUnit::assertGreaterThanOrEqual(1, $count, "No element that matches the \"{$selector}\" selector was found in the response HTML.");
				}
				else{
					PHPUnit::assertEquals(0, $count, "No element should match the \"{$selector}\" selector in the response HTML, but {$count} did match.");
				}
		
				return $this;
			},
		];

		foreach($macros as $macroName => $macroFunction){
			if( ! TestResponse::hasMacro($macroName) ){
				TestResponse::macro($macroName, $macroFunction);
			}
		}
	}
}