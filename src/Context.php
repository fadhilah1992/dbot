<?php 
/**
 * Context
 * Context Main Class
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

use Fatah\Dbot\Telegram;

final class Context
{
    public array $update;

    public Telegram $telegram;

    public function __construct(array $update, Telegram $telegram)
    {
        $this->update   = $update;
        $this->telegram = $telegram;
    }

    private function validUpdate(): bool 
    {
        return (!empty($this->update)) ? true : false;
    }

    public function reply(string $text, ?string $parse_mode = null, array $extra = [])
    {
        if ($this->validUpdate()) {
            $params = [
                'reply_to_message_id' => $this->getMessage('message_id')
            ];

            if (!is_null($parse_mode)) {
                $params['parse_mode'] = $parse_mode;
            }

            if (!empty($extra)) {
                $params = array_merge($params, $extra);
            }

            return $this->telegram->sendMessage($this->getChat('id'), $text, $params);
        }
    }

    public function replyWithHTML(string $text, array $extra = [])
    {
        if ($this->validUpdate()) {
            $params = [
                'reply_to_message_id' => $this->getMessage('message_id'),
                'parse_mode' => 'HTML'
            ];

            if (!empty($extra)) {
                $params = array_merge($params, $extra);
            }

            return $this->telegram->sendMessage($this->getChat('id'), $text, $params);
        }
    }

    public function replyWithMarkdown(string $text, array $extra = [])
    {
        if ($this->validUpdate()) {
            $params = [
                'reply_to_message_id' => $this->getMessage('message_id'),
                'parse_mode' => 'Markdown'
            ];

            if (!empty($extra)) {
                $params = array_merge($params, $extra);
            }

            return $this->telegram->sendMessage($this->getChat('id'), $text, $params);
        }
    }

    public function replyWithMarkdownV2(string $text, array $extra = [])
    {
        if ($this->validUpdate()) {
            $params = [
                'reply_to_message_id' => $this->getMessage('message_id'),
                'parse_mode' => 'MarkdownV2'
            ];

            if (!empty($extra)) {
                $params = array_merge($params, $extra);
            }

            return $this->telegram->sendMessage($this->getChat('id'), $text, $params);
        }
    }

    public function replyWithSticker(string $sticker, array $extra = [])
    {
        if ($this->validUpdate()) {
            $params = [
                'reply_to_message_id' => $this->getMessage('message_id')
            ];

            if (!empty($extra)) {
                $params = array_merge($params, $extra);
            }

            return $this->telegram->sendSticker($this->getChat('id'), $sticker, $params);
        }
    }

    public function replyWithPhoto(string $photo, ?string $caption = null, array $extra = [])
    {
        if ($this->validUpdate()) {
            $params = [
                'reply_to_message_id' => $this->getMessage('message_id')
            ];

            if (!is_null($caption)) {
                $params['caption'] = $caption;
            }

            if (!empty($extra)) {
                $params = array_merge($params, $extra);
            }

            return $this->telegram->sendPhoto($this->getChat('id'), $photo, $params);
        }
    }

    public function getMessage(string $key = null)
    {
        if (!$this->validUpdate() || !isset($this->update['message'])) {
            return null;
        }

        if (!is_null($key) && isset($this->update['message'][$key])) {
            return $this->update['message'][$key];
        } else {
            return null;
        }

        return $this->update['message'];
    }

    public function getText()
    {
        if (!$this->validUpdate()) {
            return null;
        }

        return $this->getMessage('text') ?? null;
    }

    public function getChat(string $key = null)
    {
        if (!$this->validUpdate() || is_null($this->getMessage('chat'))) {
            return null;
        }

        if (!is_null($key) && isset($this->getMessage('chat')[$key])) {
            return $this->getMessage('chat')[$key];
        } else {
            return null;
        }

        return $this->getMessage('chat');
    }

    public function getFrom(string $key = null)
    {
        if (!$this->validUpdate() || is_null($this->getMessage('from'))) {
            return null;
        }

        if (!is_null($key) && isset($this->getMessage('from')[$key])) {
            return $this->getMessage('from')[$key];
        } else {
            return null;
        }

        return $this->getMessage('from');
    }
}