<?php

use AugustoMoura\LaravelToolkit\ValueObjects\Cpf;
use Orchestra\Testbench\TestCase;

class ValueObjectsTest extends TestCase
{
	public function test_cpf_vo()
	{
		$cpf = new Cpf('401.018.870-78');
		$this->assertSame('40101887078', $cpf->apenasNumeros());
		$this->assertSame('401.018.870-78', $cpf->formatado());

		$cpf = new Cpf('40101887078');
		$this->assertSame('40101887078', $cpf->apenasNumeros());
		$this->assertSame('401.018.870-78', $cpf->formatado());

		$this->expectException(\InvalidArgumentException::class);

		$cpf = new Cpf('abc');
	}

	public function test_cpf_static_methods()
	{
		$this->assertSame('40101887078', Cpf::convertToApenasNumeros('401.018.870-78'));
		$this->assertSame('40101887078', Cpf::convertToApenasNumeros('40101887078'));

		$this->assertSame('401.018.870-78', Cpf::convertToFormatado('401.018.870-78'));
		$this->assertSame('401.018.870-78', Cpf::convertToFormatado('40101887078'));
	}
}