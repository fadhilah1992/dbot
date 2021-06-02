<?php 

namespace Fatah\Dbot\Net;

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;

/**
 * class Client
 *
 * @package    Fatah\Dbot\Net
 * @subpackage Client
 * @version    0.1
 * @since      version 0.1
 * @author     fathurrohman <https://github.com/fathurrohman26>
 *
 * HTTP Client library for make request to bot server
 */
class Client
{
	/**
	 * @var    int     $timeout
	 */
	private int $timeout;


	/**
	 * @var    string     $baseuri
	 */
	private string $baseuri = 'https://api.telegram.org/bot';

	/**
	 * @var    string     $token
	 */

	/**
	 * Client __construct
	 *
	 * @param    string    $token
	 * @param    int 	   $timeout
	 */
	public function __construct(string $token, int $timeout = 5)
	{
		$this->baseuri 	= $this->baseuri . $token . '/';
		$this->timeout 	= $timeout;
		$this->token 	= $token;
	}

	public function callApi(string $method, array $params = []): array
	{
		$options = array(
			'timeout'	=> $this->timeout,
			'base_uri'	=> $this->baseuri
		);

		$client = new \GuzzleHttp\Client($options);

		try {
			if ( !empty($params) ) {
				$result = $client->request('GET', $method, [
					'query' => $params
				]);
			} else {
				$result = $client->request('GET', $method);
			}
		} catch (RequestException $e) {
			$error = array(
				'ok' 		=> false,
				'result'	=> null,
				'error'		=> [
					'RequestException' => [
						'request'	=> str_replace($this->token, '{Telegram Bot Token}', Psr7\Message::toString($e->getRequest()))
					]
				]
			);
			if ($e->hasResponse()) {
				$error['error']['RequestException']['response'] = (string) $e->getResponse()->getBody();
			}
		} catch (TransferException $e) {
			if (isset($error)) {
				$error['error']['TransferException'] = array(
					'request'	=> Psr7\Message::toString($e->getRequest())
				);
			} else {
				$error = array(
					'ok' 		=> false,
					'result'	=> null,
					'error'		=> [
						'TransferException' => [
							'request'	=> Psr7\Message::toString($e->getRequest())
						]
					]
				);
			}
		}

		if (isset($error)) {
			return $error;
		}

		return json_decode($result->getBody(), true);
	}
}