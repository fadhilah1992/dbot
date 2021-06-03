<?php 

use Fatah\Dbot\Network\Client;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as GuzzleHttpClient;

require_once __DIR__ . '/../../vendor/autoload.php';

final class ClientTest extends TestCase 
{
	public function testClientIsInstanceOfGuzzleHttpClient(): void
	{
		$client = Client::client([
			'base_uri' => 'https://www.php.net/',
			'timeout'  => 5
		]);

		$this->assertInstanceOf(GuzzleHttpClient::class, $client);
	}

	public function testClientOptionsTypeIsArray(): void 
	{
		$client = new Client('token');

		$this->assertIsArray($client->getOptions());
	}

	public function testClientCallapiReturnTypeIsArray(): void
	{
		$client = new Client('token');

		$this->assertIsArray($client->callApi('someMethod'));
	}
}