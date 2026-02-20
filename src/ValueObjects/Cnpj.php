<?php
namespace AugustoMoura\LaravelToolkit\ValueObjects;

use AugustoMoura\LaravelToolkit\Rules\Cnpj as CnpjRule;

class Cnpj
{
	protected $apenasNumeros;

	function __construct(string $cnpj)
	{
		$this->apenasNumeros = static::convertToApenasNumeros($cnpj);

		$cnpjRule = new CnpjRule;

		$cnpjRule->validate('cnpj', $this->apenasNumeros, function($message){
			throw new \InvalidArgumentException( $message );
		});
	}

	public function apenasNumeros() : string
	{
		return $this->apenasNumeros;
	}

	public function formatado() : string
	{
		return static::convertToFormatado($this->apenasNumeros);
	}

	public static function convertToApenasNumeros(string $cnpj) : string
	{
		$cnpj = preg_replace("/[^0-9]/", "", $cnpj);
		$cnpj = str_pad($cnpj, 14, '0', STR_PAD_LEFT);
		return $cnpj;
	}

	public static function convertToFormatado(string $cnpj) : string
	{
		$cnpj = preg_replace("/\D/", '', $cnpj);
		return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "$1.$2.$3/$4-$5", $cnpj);
	}

	public function __toString()
	{
		return $this->formatado();
	}
}