<?php 

namespace Fatah\Dbot;

use Fatah\Dbot\Telegram;
use Fatah\Dbot\Net\Polling;

final class Dbot extends Telegram
{
	private array $update;

	private ?string $updateType;

	private ?int $last_update_id;

	private array $handlers;

	public function __construct(string $token, int $timeout = 5)
	{
		parent::__construct($token, $timeout);

		$this->update = [];
		$this->updateType = null;
		$this->last_update_id = null;
		$this->handlers = [];
	}

	public function updateType()
	{
		return $this->updateType;
	}

	public function on(string $updateType, callable $callback)
	{
		$this->registerHandler($updateType, $callback);
	}

	public function hears(string $pattern, callable $callback)
	{
		$this->registerHandler('text', function($update, $bot) use ($pattern, $callback) {
			$text = $update['message']['text'];
			if (preg_match('/^(\/)(.*)(\/)(.+)$/mi', $pattern)) {
				if (preg_match($pattern, $text)) {
					$callback($update, $bot);
				}
			}

			if ($text === $pattern) {
				$callback($update, $bot);
			}
		});
	}

	public function getRegistredHandlers()
	{
		return $this->handlers;
	}

	private function registerHandler(string $condition, callable $callback)
	{
		$this->handlers[$condition] []= $callback;
	}

	private function execRegistredHanlders()
	{
		foreach ($this->getRegistredHandlers() as $key => $callback) {
			if ( $this->getUpdateType($key) == $key ) {
				$this->updateType = $key;
				foreach ($this->getRegistredHandlers()[$key] as $handler) {
					call_user_func_array($handler, [ $this->update, $this ]);
				}
			}
		}
	}

	private function getUpdateType(string $message)
	{
		if (isset($this->update['message'])) {
			return $this->parseUpdateMessage($message);
		} elseif (isset($this->update['edited_message'])) {
			return 'edited_message';
		} elseif (isset($this->update['channel_post'])) {
			return 'channel_post';
		} elseif (isset($this->update['edited_channel_post'])) {
			return 'edited_channel_post';
		} elseif (isset($this->update['inline_query'])) {
			return 'inline_query';
		} elseif (isset($this->update['chosen_inline_result'])) {
			return 'chosen_inline_result';
		} elseif (isset($this->update['callback_query'])) {
			return 'callback_query';
		} elseif (isset($this->update['shipping_query'])) {
			return 'shipping_query';
		} elseif (isset($this->update['pre_checkout_query'])) {
			return 'pre_checkout_query';
		} elseif (isset($this->update['poll'])) {
			return 'poll';
		} elseif (isset($this->update['poll_answer'])) {
			return 'poll_answer';
		} elseif (isset($this->update['my_chat_member'])) {
			return 'my_chat_member';
		} elseif (isset($this->update['chat_member'])) {
			return 'chat_member';
		} else {
			return 'message';
		}
	}

	private function parseUpdateMessage(string $key)
	{
		$update = $this->update['message'];
		if (isset($update[$key]) && !empty($update[$key])) {
			return $key;
		}
		return 'message';
	}

	public function launch(bool $polling = true, int $timeout = 0)
	{
		if ($polling) {
			try {
				$this->last_update_id = null;
				$this->update = [];
				while ( true ) {
					$updates = $this->getUpdates($this->last_update_id, 100, $timeout);
					if (count($updates) > 0) {
						echo "+";
						$cup = Polling::getLastUpdate($updates);
						$this->update = $cup['update'];
						$this->last_update_id = (int) $cup['last_update_id'] + 1;

						$this->execRegistredHanlders();
					} else {
						echo "-";
					}
				}
			} catch (\Exception $e) {
				print("\n");
				print("[Dbot\Error] Ooops, encountered an error for " . $this->updateType() . " : " . $e->getMessage());
				print("\n");

				$this->last_update_id += 1;
				$this->getUpdates($this->last_update_id, 100, $timeout);

				$this->launch($polling, $timeout);
			}
		}
	}
}