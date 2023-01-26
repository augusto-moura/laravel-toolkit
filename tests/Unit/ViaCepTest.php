<?php

use AugustoMoura\LaravelToolkit\DTO\EnderecoViaCep;
use AugustoMoura\LaravelToolkit\Helpers\ViaCepAPI;
use AugustoMoura\LaravelToolkit\Traits\MakesAssertionsForObjects;
use Orchestra\Testbench\TestCase;

class ViaCepTest extends TestCase
{
	use MakesAssertionsForObjects;

	public function test_via_cep_api()
	{
		$attributes = $this->getEnderecoAttributesForTesting();

		$viaCepApi = new ViaCepAPI; 
		$viaCepApi->setMockResponse(200, json_encode($attributes));

		$endereco = $viaCepApi->buscarCep('70100000');

		$this->assertInstanceOf(EnderecoViaCep::class, $endereco);
		$this->assertObjectHasAttributes($endereco, $attributes);

		$viaCepApi->setMockResponse(200, json_encode([
			'erro' => 'true',
		]));
		$endereco = $viaCepApi->buscarCep('701000001');
		$this->assertNull($endereco);

		$viaCepApi->setMockResponse(400, '');
		$endereco = $viaCepApi->buscarCep('7010000a');
		$this->assertNull($endereco);
	}

	public function test_endereco_via_cep()
	{
		$attributes = $this->getEnderecoAttributesForTesting();
		$endereco = new EnderecoViaCep($attributes);

		$this->assertObjectHasAttributes($endereco, $attributes);
	}

	private function getEnderecoAttributesForTesting() : array
	{
		return [
			'cep' => "70100-000",
			'logradouro' => "Praça dos Três Poderes",
			'complemento' => "",
			'bairro' => "Zona Cívico-Administrativa",
			'localidade' => "Brasília",
			'uf' => "DF",
			'ibge' => "5300108",
			'gia' => "",
			'ddd' => "61",
			'siafi' => "9701"
		];
	}
}