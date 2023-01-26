<?php

use Orchestra\Testbench\TestCase;
use AugustoMoura\LaravelToolkit\Rules\Cpf;
use AugustoMoura\LaravelToolkit\Rules\MaxCharactersInHtml;
use AugustoMoura\LaravelToolkit\Rules\MaxWordsInHtml;
use AugustoMoura\LaravelToolkit\Rules\HtmlNotEmpty;

class ValidationRulesTest extends TestCase
{
    public function test_cpf_validation_rule()
    {
		$rule = new Cpf;
		$this->assertTrue($rule->passes('cpf', '40101887078'));
		$this->assertTrue($rule->passes('cpf', '401.018.870-78'));

		$this->assertFalse($rule->passes('cpf', '00000000000'));
		$this->assertFalse($rule->passes('cpf', '000.000.000-00'));

		$this->assertFalse($rule->passes('cpf', '4010.018.870-78'));
		$this->assertFalse($rule->passes('cpf', '401.1018.870-78'));
		$this->assertFalse($rule->passes('cpf', '401.018.2870-78'));
		$this->assertFalse($rule->passes('cpf', '401.018.870-878'));
    }

    public function test_max_characters_in_html_validation_rule()
    {
		$rule = new MaxCharactersInHtml(2);
		$this->assertTrue($rule->passes('html', '1'));
		$this->assertTrue($rule->passes('html', '12'));
		$this->assertFalse($rule->passes('html', '123'));

		$this->assertTrue($rule->passes('html', '<p>1</p>'));
		$this->assertTrue($rule->passes('html', '<p>12</p>'));
		$this->assertFalse($rule->passes('html', '<p>123</p>'));

		$this->assertTrue($rule->passes('html', '<p>1<span>2</span></p>'));
		$this->assertFalse($rule->passes('html', '<p>1<span>23</span></p>'));

		$rule = new MaxCharactersInHtml(6);
		$this->assertTrue($rule->passes('html', '12345'));
		$this->assertTrue($rule->passes('html', '123456'));
		$this->assertFalse($rule->passes('html', '1234567'));

		$this->assertTrue($rule->passes('html', '<p>12345</p>'));
		$this->assertTrue($rule->passes('html', '<p>123456</p>'));
		$this->assertFalse($rule->passes('html', '<p>1234567</p>'));
    }

    public function test_max_words_in_html_validation_rule()
    {
		$rule = new MaxWordsInHtml(2);
		$this->assertTrue($rule->passes('html', 'abc'));
		$this->assertTrue($rule->passes('html', 'abc def'));
		$this->assertFalse($rule->passes('html', 'abc def ghi'));

		$this->assertTrue($rule->passes('html', 'i do'));
		$this->assertTrue($rule->passes('html', "i don't"));
		$this->assertFalse($rule->passes('html', "i don't want"));

		$this->assertTrue($rule->passes('html', '"i do" '));
		$this->assertTrue($rule->passes('html', ' "i" do'));
		$this->assertFalse($rule->passes('html', '"i do" want'));

		$this->assertTrue($rule->passes('html', 'percebe-se que'));
		$this->assertFalse($rule->passes('html', "percebe-se que não"));

		$this->assertTrue($rule->passes('html', '<p>abc</p>'));
		$this->assertTrue($rule->passes('html', '<p>abc def</p>'));
		$this->assertFalse($rule->passes('html', '<p>abc def ghi</p>'));

		$rule = new MaxWordsInHtml(4);
		$this->assertTrue($rule->passes('html', 'a b c'));
		$this->assertTrue($rule->passes('html', ' a b c '));
		$this->assertTrue($rule->passes('html', 'a b c d'));
		$this->assertFalse($rule->passes('html', 'a b c d e'));
		$this->assertFalse($rule->passes('html', ' a b c d e '));

		$this->assertTrue($rule->passes('html', '<p>a b c</p>'));
		$this->assertTrue($rule->passes('html', '<p> a <span>b c</span> </p>'));
		$this->assertTrue($rule->passes('html', '<p>a <span>b c d</span></p>'));
		$this->assertFalse($rule->passes('html', '<p>a b <span>c d e</span></p>'));
		$this->assertFalse($rule->passes('html', '<p> a b <span>c d e </span></p>'));
    }

    public function test_html_not_empty_validation_rule()
    {
		$rule = new HtmlNotEmpty;
		$this->assertTrue($rule->passes('html', 'a'));
		$this->assertTrue($rule->passes('html', ' a '));
		$this->assertTrue($rule->passes('html', ' abc def '));

		$this->assertFalse($rule->passes('html', ''));
		$this->assertFalse($rule->passes('html', ' '));
		$this->assertFalse($rule->passes('html', " <br> "));
		$this->assertFalse($rule->passes('html', " <br /> "));
		$this->assertFalse($rule->passes('html', " <br/> "));
		$this->assertFalse($rule->passes('html', "<p> <br/> </p>"));

		$this->assertTrue($rule->passes('html', "a <br /> "));
		$this->assertTrue($rule->passes('html', " <br /> a "));
		$this->assertTrue($rule->passes('html', "<p> <br /> a </p>"));
		$this->assertTrue($rule->passes('html', "<p> a <br /> </p>"));
    }

}