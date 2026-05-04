<?php

namespace AugustoMoura\LaravelToolkit\Traits;
use Illuminate\Testing\Assert as PHPUnit;
use Symfony\Component\DomCrawler\Crawler;

trait MakesAssertionsForStrings
{
	protected function assertEqualsNormalizingSpaces(string $expected, string $actual)
	{
		PHPUnit::assertEquals(
			preg_replace('/[\s\t\n]{2,}/', ' ', $expected),
			preg_replace('/[\s\t\n]{2,}/', ' ', $actual),
			"Failed to assert that the strings are equal considering multiple spaces as one."
		);
	}

	/**
     * Verifica se um seletor CSS existe dentro de uma string HTML.
     */
    protected function assertStringHasMatchForSelector(string $html, string $selector, string $message = ''): void
    {
        $crawler = new Crawler($html);
        
        $matchCount = $crawler->filter($selector)->count();

        PHPUnit::assertTrue(
            $matchCount > 0,
            $message ?: "The CSS selector '{$selector}' has not been found in the provided HTML."
        );
    }

	/**
     * Verifica se múltiplos seletores CSS existem dentro de uma string HTML.
     */
    protected function assertStringHasMatchesForMultipleSelectors(string $html, array $selectors, string $message = ''): void
    {
        $crawler = new Crawler($html);

        foreach ($selectors as $selector) {
            $matchCount = $crawler->filter($selector)->count();

            $this->assertTrue(
                $matchCount > 0,
                $message ?: "The CSS selector '{$selector}' has not been found in the provided HTML."
            );
        }
    }
    
    /**
     * Verifica se o seletor contém um texto específico.
     */
    protected function assertSelectorContainsTextInString(string $selector, string $text, string $html): void
    {
        $crawler = new Crawler($html);
        $matches = $crawler->filter($selector);
        
        PHPUnit::assertTrue($matches->count() > 0, "The CSS selector '{$selector}' has not been found in the provided HTML.");
		PHPUnit::assertStringContainsString($text, $matches->first()->text());
    }

	/**
     * Asserts that a given text is NOT present in any occurrence of a selector.
     */
    protected function assertSelectorDoesntContainTextInString(string $selector, string $text, string $html, string $message = ''): void
    {
        $crawler = new Crawler($html);
        $matches = $crawler->filter($selector);

        // 1. Ensures the selector exists in the HTML (prevents false positives if the selector is wrong)
        $this->assertTrue(
            $matches->count() > 0,
            "The selector '{$selector}' was not found in the HTML, so it was not possible to assert its text."
        );

        // 2. Iterates over all occurrences (matches) found for the selector
        $matches->each(function (Crawler $node, $i) use ($selector, $text, $message) {
            $this->assertStringNotContainsString(
                $text,
                $node->text(),
                $message ?: "The text '{$text}' was unexpectedly found in the selector '{$selector}' (occurrence " . ($i + 1) . ")."
            );
        });
    }

    /**
     * Asserts that a CSS selector does NOT exist in the HTML string.
     */
    protected function assertSelectorHasNoMatchInString(string $selector, string $html, string $message = ''): void
    {
        $crawler = new Crawler($html);
        
        $this->assertEquals(
            0,
            $crawler->filter($selector)->count(),
            $message ?: "The CSS selector '{$selector}' should not be present in the HTML, but it was found."
        );
    }

}