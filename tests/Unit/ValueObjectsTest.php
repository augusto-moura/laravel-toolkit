<?php

use AugustoMoura\LaravelToolkit\ValueObjects\Cpf;
use AugustoMoura\LaravelToolkit\ValueObjects\Cnpj;
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

	public function test_cnpj_vo()
	{
		$cnpj = new Cnpj('11.222.333/0001-81');
		$this->assertSame('11222333000181', $cnpj->apenasAlfanumericos());
		$this->assertSame('11.222.333/0001-81', $cnpj->formatado());

		$cnpj = new Cnpj('11222333000181');
		$this->assertSame('11222333000181', $cnpj->apenasAlfanumericos());
		$this->assertSame('11.222.333/0001-81', $cnpj->formatado());
	}

	public function test_cnpj_vo_invalid_throws_exception()
	{
		$this->expectException(\InvalidArgumentException::class);
		new Cnpj('00000000000000'); // Invalid sequence
	}

	public function test_cnpj_static_methods()
	{
		// Numeric CNPJ
		$this->assertSame('11222333000181', Cnpj::convertToApenasAlfanumericos('11.222.333/0001-81'));
		$this->assertSame('11222333000181', Cnpj::convertToApenasAlfanumericos('11222333000181'));

		// Alphanumeric CNPJ
		$this->assertSame('A1222333000181', Cnpj::convertToApenasAlfanumericos('A1.222.333/0001-81'));
		
		$this->assertSame('11.222.333/0001-81', Cnpj::convertToFormatado('11.222.333/0001-81'));
		$this->assertSame('11.222.333/0001-81', Cnpj::convertToFormatado('11222333000181'));
	}
}
