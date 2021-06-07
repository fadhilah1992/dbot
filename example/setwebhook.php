<?php 

use Fatah\Dbot\Dbot;

require_once __DIR__ . '/../vendor/autoload.php';

$bot = new Dbot('BOT-TOKEN');


$bot->setWebhook('https://myhost.example/handle_bot.php');
var_dump($bot->getWebhookInfo());

// custom certificate file
$bot->setWebhook('https://myhost.example/handle_bot.php', __DIR__ . '/cert.pem');
var_dump($bot->getWebhookInfo());

// delete webhook
echo "delete webhook";
$bot->deleteWebhook(true);
var_dump($bot->getWebhookInfo());

