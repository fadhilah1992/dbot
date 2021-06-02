<?php 

namespace Fatah\Dbot;

/**
 * class Telegram
 *
 * @package    Fatah\Dbot
 * @subpackage Telegram
 * @version    0.1
 * @since      version 0.1
 * @author     fathurrohman <https://github.com/fathurrohman26>
 *
 * Telegram Main Class
 */
class Telegram 
{
	/**
	 * @var    string    $token
	 */
	private string $token;

	/**
	 * @var    \Fatah\Dbot\Net\Client    $client
	 */
	private \Fatah\Dbot\Net\Client $client;

	/**
	 * Telegram Constructor
	 * 
	 * @param     string          $token       Telegram bot token
	 * @param     int|integer     $timeout     Request timeout
	 */
	public function __construct(string $token, int $timeout = 5)
	{
		$this->setToken($token);
		$this->client = new \Fatah\Dbot\Net\Client($this->token, $timeout);
	}

	/**
	 * set token
	 * 
	 * @param     string     $token
	 * @return    void
	 */
	public function setToken(string $token)
	{
		$this->token = $token;
	}

	/**
	 * A simple method for testing your bot's auth token. Requires no parameters. 
	 * Returns basic information about the bot in form of a User object.
	 * 
	 * @return array
	 */
	public function getMe()
	{
		return $this->callApi('getMe');
	}

	/**
	 * Use this method to log out from the cloud Bot API server before launching the bot locally. You must log out the bot before running it locally, otherwise there is no guarantee that the bot will receive updates. After a successful call, you can immediately log in on a local server, but will not be able to log in back to the cloud Bot API server for 10 minutes. Returns True on success. Requires no parameters.
	 * 
	 * @return  boll
	 */
	public function logOut(): bool 
	{
		return (bool) $this->callApi('logOut');
	}

	/**
	 * Use this method to close the bot instance before moving it from one local server to another. You need to delete the webhook before calling this method to ensure that the bot isn't launched again after server restart. The method will return error 429 in the first 10 minutes after the bot is launched. Returns True on success. Requires no parameters.
	 * 
	 * @return true
	 */
	public function close(): bool 
	{
		return (bool) $this->callApi('close');
	}

	/**
	 * Use this method to receive incoming updates using long polling (wiki). An Array of Update objects is returned.
	 * 
	 * @param  int|null    $offset          Identifier of the first update to be returned. Must be greater by one than the highest among the identifiers of previously received updates. By default, updates starting with the earliest unconfirmed update are returned. An update is considered confirmed as soon as getUpdates is called with an offset higher than its update_id. The negative offset can be specified to retrieve updates starting from -offset update from the end of the updates queue. All previous updates will forgotten.
	 * @param  int|integer $limit            Limits the number of updates to be retrieved. Values between 1-100 are accepted. Defaults to 100.
	 * @param  int|integer $timeout         Timeout in seconds for long polling. Defaults to 30, i.e. usual short polling. Should be positive, short polling should be used for testing purposes only.
	 * @param  array       $allowed_updates A JSON-serialized list of the update types you want your bot to receive. For example, specify [“message”, “edited_channel_post”, “callback_query”] to only receive updates of these types. See Update for a complete list of available update types. Specify an empty list to receive all update types except chat_member (default). If not specified, the previous setting will be used. Please note that this parameter doesn't affect updates created before the call to the getUpdates, so unwanted updates may be received for a short period of time.
	 * 
	 * @return array
	 */
	public function getUpdates(int $offset = null, int $limit = 100, int $timeout = 30, array $allowed_updates = []): array
	{
		$options = [
			'limit'		=> $limit,
			'timeout'	=> $timeout
		];

		if (!is_null($offset)) {
			$options['offset'] = $offset;
		}

		if (!empty($allowed_updates)) {
			$options['allowed_updates'] = $allowed_updates;
		}

		return $this->callApi('getUpdates', $options);
	}

	/**
	 * Use this method to specify a url and receive incoming updates via an outgoing webhook. Whenever there is an update for the bot, we will send an HTTPS POST request to the specified url, containing a JSON-serialized Update. In case of an unsuccessful request, we will give up after a reasonable amount of attempts. Returns True on success.
	 * If you'd like to make sure that the Webhook request comes from Telegram, we recommend using a secret path in the URL, e.g. https://www.example.com/<token>. Since nobody else knows your bot's token, you can be pretty sure it's us.
	 * 
	 * @param string               $url                  HTTPS url to send updates to. Use an empty string to remove webhook integration
	 * @param Types\InputFile|null $certificate          Upload your public key certificate so that the root certificate in use can be checked. See our self-signed guide for details.
	 * @param string|null          $ip_address           The fixed IP address which will be used to send webhook requests instead of the IP address resolved through DNS
	 * @param int|null             $max_connections      Maximum allowed number of simultaneous HTTPS connections to the webhook for update delivery, 1-100. Defaults to 40. Use lower values to limit the load on your bot's server, and higher values to increase your bot's throughput.
	 * @param array                $allowed_updates      A JSON-serialized list of the update types you want your bot to receive. For example, specify [“message”, “edited_channel_post”, “callback_query”] to only receive updates of these types. See Update for a complete list of available update types. Specify an empty list to receive all update types except chat_member (default). If not specified, the previous setting will be used. Please note that this parameter doesn't affect updates created before the call to the setWebhook, so unwanted updates may be received for a short period of time.
	 * @param bool|boolean         $drop_pending_updates Pass True to drop all pending updates
	 *
	 * @return bool
	 */
	public function setWebhook(string $url, ?array $certificate = null, string $ip_address = null, int $max_connections = null, array $allowed_updates = [], bool $drop_pending_updates = false): bool
	{
		$options = [
			'url'	=> $url
		];

		if (!is_null($certificate)) {
			$options['certificate'] = $certificate;
		}

		if (!is_null($ip_address)) {
			$options['ip_address'] = $ip_address;
		}

		if (!is_null($max_connections)) {
			$options['max_connections'] = $max_connections;
		}

		if (!empty($allowed_updates)) {
			$options['allowed_updates'] = $allowed_updates;
		}

		if ($drop_pending_updates) {
			$options['drop_pending_updates'] = true;
		}

		return (bool) $this->callApi('setWebhook', $options);
	}

	/**
	 * Use this method to remove webhook integration if you decide to switch back to getUpdates. Returns True on success.
	 * 
	 * @param  bool|boolean $drop_pending_updates Pass True to drop all pending updates
	 * 
	 * @return boll
	 */
	public function deleteWebhook(bool $drop_pending_updates = false): bool
	{
		$options = [];
		if (false !== $drop_pending_updates) {
			$options['drop_pending_updates'] = $drop_pending_updates;
		}

		return (bool) $this->callApi('deleteWebhook', $options);
	}

	/**
	 * Use this method to get current webhook status. Requires no parameters. On success, returns a WebhookInfo object. If the bot is using getUpdates, will return an object with the url field empty.
	 * 
	 * @return array
	 */
	public function getWebhookInfo()
	{
		return $this->callApi('getWebhookInfo');
	}

	/**
	 * [Use this method to send text messages. On success, the sent Message is returned.
	 * 
	 * @param  string $chat_id    [description]
	 * @param  string $text       [description]
	 * @param  string $parse_mode [description]
	 * @param  array  $extra      [description]
	 * 
	 * @return array
	 */
	public function sendMessage($chat_id, string $text, ?string $parse_mode = null, array $extra = [])
	{
		$options = [
			'chat_id'	=> $chat_id,
			'text'		=> $text
		];

		if (!is_null($parse_mode)) {
			$options['parse_mode'] = $parse_mode;
		}

		if (!empty($extra)) {
			$options = array_merge($options, $extra);
		}

		return $this->callApi('sendMessage', $options);
	}


	public function callApi(string $method, array $params = []): array
	{
		$request = $this->client->callApi($method, $params);
		if (true === (bool) $request['ok']) {
			return $request['result'];
		}
		return $this->trigger_error($request);
	}

	private function trigger_error(array $result)
	{
		$error = isset($result['error']['RequestException']) ? $result['error']['RequestException'] : $result['error']['TransferException']['request'];

		if (is_string($error)) {
			$message = $error;
		} else {
			if (isset($error['response'])) {
				$message = json_decode($error['response'], true);
				$message = $message['description'];
			} else {
				if (isset($error['request'])) {
					$message = $error['request'];
				} else {
					$message = "Unknown error !";
				}
			}
		}
		print("\n");
		echo("[Dbot\Error] Telegram Error: " . $message);
		print("\n");
		return [];
	}
}