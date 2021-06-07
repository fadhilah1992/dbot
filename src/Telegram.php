<?php 
/**
 * Telegram
 * Telegram Main Class
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
use Fatah\Dbot\Network\Client;

class Telegram
{
    private Client $client;

    public function __construct(string $token, $options = [])
    {
        $this->client = new Client($token, $options);
    }

    public function request(string $method, array $params = []): array
    {
        $request = $this->client->callApi($method, $params);
        if ((bool)$request['ok'] === true) {
            return $request['result']['result'];
        } else {
            // error response
            return $request;
        }
    }

    public function upload(string $method, array $file, array $params = [])
    {
        $request = $this->client->uploadFile($method, $file, $params);
        if ((bool)$request['ok'] === true) {
            return $request['result']['result'];
        }
        return $request;
    }

    public function getMe(): array
    {
        return $this->request('getMe');
    }

    public function getUpdates(?int $offset = null, int $limit = 100, int $timeout = 30, array $allowed_updates = []): array
    {
        $params = [
            'limit'   => $limit,
            'timeout' => $timeout
        ];

        if (!is_null($offset)) {
            $params['offset'] = $offset;
        }

        if (!empty($allowed_updates)) {
            $params['allowed_updates'] = $allowed_updates;
        }

        return $this->request('getUpdates', $params);
    }

    public function sendMessage($chat_id, string $text, array $extra = []): array
    {
        $params = [
            'chat_id' => $chat_id,
            'text'    => $text
        ];

        if (!empty($extra)) {
            $params = array_merge($params, $extra);
        }

        return $this->request('sendMessage', $params);
    }

    public function sendSticker($chat_id, $sticker, array $extra = [])
    {
        $params = [
            'chat_id' => $chat_id,
            'sticker' => $sticker
        ];

        if (!empty($extra)) {
            $params = array_merge($params, $extra);
        }

        return $this->request('sendSticker', $params);
    }

    public function sendPhoto($chat_id, string $photo, array $extra = [])
    {
        $params = [
            'chat_id' => $chat_id
        ];

        if (!empty($extra)) {
            $params = array_merge($params, $extra);
        }

        if (is_file($photo)) {
            $file = [
                'file_name' => 'photo',
                'file_path' => $photo
            ];
            return $this->upload('sendPhoto', $file, $params);
        }

        $params['photo'] = $photo;
        return $this->request('sendPhoto', $params);
    }

    public function getWebhookInfo()
    {
        return $this->request('getWebhookInfo');
    }
}