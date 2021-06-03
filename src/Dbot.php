<?php 
/**
 * Dbot
 * Dbot Main Class
 * php version 8.0.7
 *
 * @category Main
 * @package  Dbot
 * @author   fathurrohman <fathurrohmanrosyadi@gmail.com>
 * @license  https://github.com/fathurrohman26/dbot/blob/main/LICENSE MIT License
 * @version  GIT: @0.1@
 * @link     https://github.com/fathurrohman26/dbot
 */

namespace Fatah\Dbot;

use Exception;

use Fatah\Dbot\Context;
use Fatah\Dbot\Telegram;

use Fatah\Dbot\Network\Client;
use Fatah\Dbot\Network\Polling;

final class Dbot extends Telegram
{
	private array $options;

	private array $update;

	private array $handlers;

	private bool $verbose;

	private Telegram $telegram;

	private ?string $updateType;
	private ?string $updateSubType;

	private $catch;

	public function __construct(string $token, array $options = [])
	{
		$this->options = [];

		if (isset($options['verbose'])) {
			if (is_bool($options['verbose'])) {
				$this->verbose($options['verbose']);    
			} else {
				$this->verbose();
			}
		} else {
			$this->verbose();
		}

		if (!isset($options['timeout'])) {
			$this->options['timeout'] = 5;
		} else {
			if (is_int($options['timeout'])) {
				$this->options['timeout'] = $options['timeout'];
			} else {
				$this->options['timeout'] = 5;
			}
		}

		$this->validateToken($token);
		$this->update = [];

		$this->catch(function($updateType, $e){
			print("\n");
			print("[Dbot\Error] Sepertinya telah terjadi error untuk tipe update (".$updateType.") Error Message: " . $e->getMessage());
			print("\n");
		});

		$this->telegram = new Telegram($token, $this->options);

		parent::__construct($token, $this->options);
	}

	private function validateToken(string $token): void
	{
		$x = explode(':', $token);
		if (count($x) !== 2) {
			die("Sepertinya token bot tidak valid!");
		} else {

			if (intval($x[0]) === 0) {
				die("Sepertinya token bot tidak valid!");
			}

			if (is_string($x[1]) === false) {
				die("Sepertinya token bot tidak valid!");
			}
		}
	}

	public function verbose(bool $value = true): void
	{
		$this->verbose = $value;
	}

	public function catch(callable $handler)
	{
		$this->catch = $handler;
	}

	public function on(string $updateType, callable $callback): void
	{
		$this->registerHandler($updateType, $callback);
	}

	public function hears(string $pattern, callable $callback)
	{
		$this->registerHandler('text', function($ctx) use ($pattern, $callback) {
			$text = $ctx->getText();
			if (preg_match('/^(\/)(.*)(\/)(.+)$/mi', $pattern)) {
				if (preg_match($pattern, $text)) {
					$callback($ctx);
				}
			}

			if ($text === $pattern) {
				$callback($ctx);
			}
		});
	}

	public function launch(bool $polling = true, array $options = [])
	{
		if ($polling) {
			$args = [
				'offset'  => null,
				'limit'   => 100,
				'timeout' => 0,
				'allowed_updates' => []
			];

			if (!empty($options)) {
				$args = array_merge($args, $options);
			}

			$this->update = [];
			try {
				while ( true ) {
					$updates = $this->getUpdates($args['offset'], $args['limit'], $args['timeout'], $args['allowed_updates']);
					if (count($updates) > 0) {
						if ($this->verbose) {
							echo "+";
							$lup = $this->getLastUpdate($updates);

							$this->update   = $lup['update'];
							$args['offset'] = $lup['last_update_id'] + 1;

							$this->updateType = $this->updateType();

							$this->execRegistredHanlders();
						}
					} else {
						if ($this->verbose) {
							echo "-";
						}
					}
					sleep(0.5);
				}
			} catch (Exception $e) {
				$updateType = !is_null($this->updateSubType) ? $this->updateSubType : $this->updateType;
				call_user_func_array($this->catch, [ $updateType, $e ]);

				$args['offset'] += 1;
				$this->getUpdates($args['offset'], $args['limit'], $args['timeout'], $args['allowed_updates']);
				$this->launch($polling, $options);
			}
		}
	}

	private function condition(string $cond): bool
	{
		if ($this->updateType() === $cond) {
			return true;
		} elseif ($this->updateSubType($cond)) {
			return true;
		}
		return false;
	}

	private function getLastUpdate(array $updates): array
	{
		$update_ids = [];

		foreach ($updates as $update) {
			$update_ids []= $update['update_id'];
		}

		$max = max($update_ids);
		foreach ($updates as $update) {
			$update = $update;
			if ($update['update_id'] == $max) {
				return [
					'last_update_id' => $max,
					'update' => $update
				];
			}
		}
	}

	private function updateType(): ?string
	{
		if (!empty($this->update)) {
			if (isset($this->update['edited_message'])) {
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
		return null;
	}

	private function updateSubType(string $type): bool
	{
		if (!empty($this->updateType()) && $this->updateType() === 'message') {
			if (isset($this->update['message'][$type]) && !empty($this->update['message'][$type])) {
				$this->updateSubType = $type;
				return true;
			}
		}
		return false;
	}

	private function registerHandler(string $updateType, callable $callback): void
	{
		$this->handlers[$updateType] []= $callback;
	}

	private function execRegistredHanlders()
	{
		foreach ($this->handlers as $key => $cbs) {
			if ($this->condition($key)) {
				foreach ($cbs as $callback) {
					call_user_func_array($callback, [ new Context($this->update, $this->telegram) ]);
				}
			}
		}
	}
}