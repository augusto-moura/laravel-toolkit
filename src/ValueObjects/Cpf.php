<?php
namespace AugustoMoura\LaravelToolkit\ValueObjects;

use AugustoMoura\LaravelToolkit\Rules\Cpf as CpfRule;

class Cpf
{
	protected $apenasNumeros;

	function __construct(string $cpf)
	{
		$this->apenasNumeros = static::convertToApenasNumeros($cpf);

		$cpfRule = new CpfRule;
		if( ! $cpfRule->passes('cpf', $this->apenasNumeros) ){
			throw new \InvalidArgumentException( "{$cpf} não é um valor de CPF válido." );
		}
	}

	public function apenasNumeros() : string
	{
		return $this->apenasNumeros;
	}

	public function formatado() : string
	{
		return static::convertToFormatado($this->apenasNumeros);
	}

	public static function convertToApenasNumeros(string $cpf) : string
	{
		$cpf = preg_replace("/[^0-9]/", "", $cpf);
		$cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
		return $cpf;
	}

	public static function convertToFormatado(string $cpf) : string
	{
		$cpf = preg_replace("/\D/", '', $cpf);
		return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cpf);
	}
}