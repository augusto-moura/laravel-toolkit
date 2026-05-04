<?php
namespace AugustoMoura\LaravelToolkit\ValueObjects;

use AugustoMoura\LaravelToolkit\Rules\Cep as CepRule;

class Cep
{
	protected $apenasNumeros;

	function __construct(string $cep)
	{
		$this->apenasNumeros = preg_replace('/\D/', '', $cep);

		$cepRule = new CepRule;
		$cepRule->validate(
			'cep',
			$this->formatado(),
			function() use(&$passed){
				throw new \InvalidArgumentException( "O CEP não é um valor de CEP válido." );
			}
		);
	}

	public function apenasNumeros() : string
	{
		return $this->apenasNumeros;
	}

	public function formatado() : string
	{
		return static::formatar($this->apenasNumeros);
	}

	public static function formatar(string $cep) : string
	{
		$cep = preg_replace("/\D/", '', $cep);
		return preg_replace("/(\d{5})(\d{3})/", "\$1-\$2", $cep);
	}

	public function __toString()
	{
		return $this->formatado();
	}
}