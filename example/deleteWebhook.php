<?php 

use Fatah\Dbot\Dbot;

require_once __DIR__ . '/../vendor/autoload.php';

$bot = new Dbot('BOT-TOKEN');

$bot->deleteWebhook();

var_dump($bot->getWebhookInfo());
