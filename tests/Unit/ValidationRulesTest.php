<?php

use Orchestra\Testbench\TestCase;
use AugustoMoura\LaravelToolkit\Rules\Cpf;
use AugustoMoura\LaravelToolkit\Rules\Cep;
use AugustoMoura\LaravelToolkit\Rules\MaxCharactersInHtml;
use AugustoMoura\LaravelToolkit\Rules\MaxWordsInHtml;
use AugustoMoura\LaravelToolkit\Rules\HtmlNotEmpty;
use AugustoMoura\LaravelToolkit\Rules\BrazilPhoneNumber;
use AugustoMoura\LaravelToolkit\Rules\HourAndMinute;
use AugustoMoura\LaravelToolkit\Traits\MakesAssertionsForValidationRules;

class ValidationRulesTest extends TestCase
{
	use MakesAssertionsForValidationRules;

    public function test_cpf_validation_rule()
    {
		$rule = new Cpf;

		$this->assertValidationRuleForMultipleValues($rule, [
			'40101887078' => true,
			'401.018.870-78' => true,
			'00000000000' => false,
			'000.000.000-00' => false,
			'4010.018.870-78' => false,
			'401.1018.870-78' => false,
			'401.018.2870-78' => false,
			'401.018.870-878' => false,
		]);
    }

	public function test_cep_validation_rule()
    {
		$rule = new Cep;

		$this->assertValidationRuleForMultipleValues($rule, [
			'12345-123' => true,
			'00001-001' => true,
			'1234-001' => false,
			'123456-001' => false,
			'12345-12' => false,
			'12345-1234' => false,
			'12345123' => false,
			'1234H-123' => false,
		]);
    }

    public function test_max_characters_in_html_validation_rule()
    {
		$rule = new MaxCharactersInHtml(2);

		$this->assertValidationRuleForMultipleValues($rule, [
			'1' => true,
			'12' => true,
			'123' => false,

			'<p>1</p>' => true,
			'<p>12</p>' => true,
			'<p>123</p>' => false,

			'<p>1<span>2</span></p>' => true,
			'<p>1<span>23</span></p>' => false,
		]);

		$rule = new MaxCharactersInHtml(6);

		$this->assertValidationRuleForMultipleValues($rule, [
			'12345' => true,
			'123456' => true,
			'1234567' => false,

			'<p>12345</p>' => true,
			'<p>123456</p>' => true,
			'<p>1234567</p>' => false,
		]);
    }

    public function test_max_words_in_html_validation_rule()
    {
		$rule = new MaxWordsInHtml(2);
		
		$this->assertValidationRuleForMultipleValues($rule, [
			'abc' => true,
			'abc def' => true,
			'abc def ghi' => false,
			'i do' => true,
			"i don't" => true,
			"i don't want" => false,
			'"i do" ' => true,
			' "i" do' => true,
			'"i do" want' => false,
			'percebe-se que' => true,
			"percebe-se que não" => false,
			'<p>abc</p>' => true,
			'<p>abc def</p>' => true,
			'<p>abc def ghi</p>' => false,
		]);

		$rule = new MaxWordsInHtml(4);

		$this->assertValidationRuleForMultipleValues($rule, [
			'a b c' => true,
			' a b c ' => true,
			'a b c d' => true,
			'a b c d e' => false,
			' a b c d e ' => false,

			'<p>a b c</p>' => true,
			'<p> a <span>b c</span> </p>' => true,
			'<p>a <span>b c d</span></p>' => true,
			'<p>a b <span>c d e</span></p>' => false,
			'<p> a b <span>c d e </span></p>' => false,
		]);
    }

    public function test_html_not_empty_validation_rule()
    {
		$rule = new HtmlNotEmpty;
		
		$this->assertValidationRuleForMultipleValues($rule, [
			'a' => true,
			' a ' => true,
			' abc def ' => true,
			'' => false,
			' ' => false,
			" <br> " => false,
			" <br /> " => false,
			" <br/> " => false,
			"<p> <br/> </p>" => false,
			"a <br /> " => true,
			" <br /> a " => true,
			"<p> <br /> a </p>" => true,
			"<p> a <br /> </p>" => true,
		]);
    }

    public function test_brazil_phone_number_validation_rule()
    {
		$rule = new BrazilPhoneNumber;

		$this->assertValidationRuleForMultipleValues($rule, [
			'061912341234' => true,
			'061 91234 1234' => true,
			'61 91234 1234' => true,
			'61 1234 1234' => true,
			'(61) 1234 1234' => true,
			'(61) 91234 1234' => true,
			'+5561912341234' => true,
			'+55 61 91234 1234' => true,
			'+55 61 912341234' => true,
			'5561912341234' => true,
			'5561 912341234' => true,
			'5561 91234 1234' => true,
			'5561 1234 1234' => true,
			'05561 1234 1234' => false,
			'061 A234 1234' => false,
			'1234 1234' => false,
			'91234 1234' => false,
			'(61 1234 1234' => false,
			'61) 1234 1234' => false,
		]);
    }

    public function test_hour_and_minute_validation_rule()
    {
		$rule = new HourAndMinute;

		$this->assertValidationRuleForMultipleValues($rule, [
			'00:00' => true,
			'01:01' => true,
			'23:59' => true,
			'23:60' => false,
			'24:00' => false,
			'a0:00' => false,
			'01:a0' => false,
			'01.01' => false,
			'-2:01' => false,
			'01:-2' => false,
			'001:01' => false,
			'01:012' => false,
			'4:4' => false,
		]);
    }

}