<?php

namespace AugustoMoura\LaravelToolkit\DTO;

class EnderecoViaCep
{
	public $cep;
	public $logradouro;
	public $complemento;
	public $bairro;
	public $localidade;
	public $uf;
	public $ibge;
	public $gia;
	public $ddd;
	public $siafi;

	function __construct($attributes)
	{
		$this->cep = data_get($attributes, 'cep');
		$this->logradouro = data_get($attributes, 'logradouro');
		$this->complemento = data_get($attributes, 'complemento');
		$this->bairro = data_get($attributes, 'bairro');
		$this->localidade = data_get($attributes, 'localidade');
		$this->uf = data_get($attributes, 'uf');
		$this->ibge = data_get($attributes, 'ibge');
		$this->gia = data_get($attributes, 'gia');
		$this->ddd = data_get($attributes, 'ddd');
		$this->siafi = data_get($attributes, 'siafi');
	}
}