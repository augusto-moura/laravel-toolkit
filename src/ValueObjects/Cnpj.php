<?php
namespace AugustoMoura\LaravelToolkit\ValueObjects;

use AugustoMoura\LaravelToolkit\Rules\Cnpj as CnpjRule;

class Cnpj
{
	protected string $apenasAlfanumericos;

	function __construct(string $cnpj)
	{
		$this->apenasAlfanumericos = static::convertToApenasAlfanumericos($cnpj);

		$cnpjRule = new CnpjRule;

		$cnpjRule->validate('cnpj', $this->apenasAlfanumericos, function($message){
			throw new \InvalidArgumentException( $message );
		});
	}

	public function apenasAlfanumericos(): string
    {
        return $this->apenasAlfanumericos;
    }

	public function formatado() : string
	{
		return static::convertToFormatado($this->apenasAlfanumericos);
	}

	public static function convertToFormatado(string $cnpj) : string
	{
		if (strlen($cnpj) !== 14) {
            return $cnpj;
        }

        return preg_replace(
            '/^([A-Z0-9]{2})([A-Z0-9]{3})([A-Z0-9]{3})([A-Z0-9]{4})([0-9]{2})$/',
            '$1.$2.$3/$4-$5',
            $cnpj
        );
	}

	public static function convertToApenasAlfanumericos(string $cnpj) : string
	{
		$cnpj = str($cnpj)
			->upper()
			->replaceMatches("/[^0-9A-Z]/", "")
			->padLeft(14, '0')
			->toString();
		return $cnpj;
	}

	public function __toString()
	{
		return $this->formatado();
	}

	/**************************************************
	***************************************************
	****************** //MARK: Retrocompatibilidade
	***************************************************
	***************************************************/

	public function apenasNumeros() : string
	{
		return $this->apenasAlfanumericos;
	}

	public static function convertToApenasNumeros(string $cnpj) : string
	{
		$cnpj = preg_replace("/[^0-9]/", "", $cnpj);
		$cnpj = str_pad($cnpj, 14, '0', STR_PAD_LEFT);
		return $cnpj;
	}

}