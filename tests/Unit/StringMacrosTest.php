<?php

use AugustoMoura\LaravelToolkit\Providers\LaravelToolkitServiceProvider;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

class StringMacrosTest extends TestCase
{
	protected function getPackageProviders($app)
    {
        return [LaravelToolkitServiceProvider::class];
    }

    public function test_title_with_spaces()
    {
		$inputAndExpected = [
			'word' => 'Word',
			'two words' => 'Two Words',
			'three fine words' => 'Three Fine Words',
			'threeWordsTogether' => 'Three Words Together',
			'ThreeWordsTogether' => 'Three Words Together',
		];

		$this->testStringMacroForArray('titleWithSpaces', $inputAndExpected);
	}

    public function test_super_trim()
    {
        $inputAndExpected = [
			' text 123 ' => 'text 123',
			"\ttext 123\t" => "text 123",
			"\ntext 123\n" => "text 123",
			"\rtext 123\r" => "text 123",
			"\0text 123\0" => "text 123",
			"\x0Btext 123\x0B" => "text 123",
			"\x0Btext 123\x0B" => "text 123",
			"\xC2text 123\xC2" => "text 123",
			"\xA0text 123\xA0" => "text 123",
			"\t\n\r \0\x0B\xC2\xA0text 123\t\n \r\0\x0B\xC2\xA0" => "text 123",
		];

		$this->testStringMacroForArray('superTrim', $inputAndExpected);
	}

    public function test_word_wrap_without_breaking_words()
    {
        $inputAndExpected = [
			'1234 12345' => ['1234', '12345'],
			'12345 12345' => ['12345', '12345'],
			'1 2 3 12345' => ['1 2 3', '12345'],
			'1 2 12345' => ['1 2', '12345'],
			'12345 1 2 3 12345' => ['12345', '1 2 3', '12345'],
		];

		$this->testWordWrapWithoutBreakingWordsForArray(5, $inputAndExpected);

		$this->expectException(\LengthException::class);

		Str::wordWrapWithoutBreakingWords('123456 12345', 5);
	}

	private function testStringMacroForArray(string $macro, array $inputAndExpected)
	{
		foreach($inputAndExpected as $input => $expected) {
			$this->assertEquals($expected, Str::$macro($input));
			
			$stringable = Str::of($input)->$macro();
			$this->assertEquals($expected, (string) $stringable);
			$this->assertInstanceOf(Stringable::class, $stringable);
		}
	}

	private function testWordWrapWithoutBreakingWordsForArray(
		int $charLimitPerLine, 
		array $inputAndExpected
	)
	{
		foreach($inputAndExpected as $input => $expected) {
			$this->assertEquals($expected, Str::wordWrapWithoutBreakingWords($input, $charLimitPerLine));

			$arrayParts = Str::of($input)->wordWrapWithoutBreakingWords($charLimitPerLine);
			$this->assertIsArray($arrayParts);
			$this->assertEquals($expected, $arrayParts);
		}
	}

}