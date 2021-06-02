<?php 

namespace Fatah\Dbot\Net;

/**
 * class Polling
 *
 * @package    Fatah\Dbot\Net
 * @subpackage Polling
 * @version    0.1
 * @since      version 0.1
 * @author     fathurrohman <https://github.com/fathurrohman26>
 *
 * Polling Main Class
 */
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
			'update' => $update
		];
	}
}