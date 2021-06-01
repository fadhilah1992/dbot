<?php 

namespace Fatah\Dbot\Net;

use Fatah\Dbot\Telegram\Types;

class Polling
{
	public static function getLastUpdate(array $updates): array
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

		return [
			'last_update_id' => $max,
			'update' => new $update
		];
	}
}