<?php
namespace AugustoMoura\LaravelToolkit\Helpers;

use AugustoMoura\LaravelToolkit\DTO\EnderecoViaCep;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class ViaCepAPI
{
	private $mockResponse = null;

	public function apiUrl() : string
	{
		return 'https://viacep.com.br';
	}

	public function api() : Client
	{
		$sslCertPath = config('app.ssl_cert_path', false);
		$mockHandler = $this->getMockHandler();

		return new Client(array_merge(
			[
				'base_uri' => self::apiUrl(),
			],
			$sslCertPath ? ['verify' => $sslCertPath] : [],
			$mockHandler ? ['handler' => $mockHandler] : []
		));
	}

	public function buscarCep(string $cep) : ?EnderecoViaCep 
	{
		try{
			$response = self::api()->request('GET', "ws/{$cep}/json");
			$statusCode = $response->getStatusCode();

			if($statusCode != 200)
				return null;
			
			$responseJson = (string) $response->getBody();
			$addressAsObject = json_decode($responseJson);
			
			$erro = data_get($addressAsObject, 'erro', null);
			if($erro && $erro == 'true')
				return null;
	
			return new EnderecoViaCep($addressAsObject);
		}
		catch(\Throwable $e){
			return null;
		}
	}

	public static function staticBuscarCep(string $cep) : ?EnderecoViaCep
	{
		$viaCepApi = new self();
		return $viaCepApi->buscarCep($cep);
	}

	public function setMockResponse(int $statusCode, string $contentJson)
	{
		$this->mockResponse = new Response(
			$statusCode, 
			['Content-Type' => 'application/json'], 
			$contentJson
		);
	}

	private function getMockHandler() : ?MockHandler
	{
		return $this->mockResponse ? 
			new MockHandler([$this->mockResponse]) :
			null
		;
	}
}