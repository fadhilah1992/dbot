<?php 

use Fatah\Dbot\Dbot;

require_once __DIR__ . '/../vendor/autoload.php';

$bot = new Dbot('BOT-TOKEN');


$bot->setWebhook('url-webhook');
var_dump($bot->getWebhookInfo());

// custom certificate file
/**
 * $bot->setWebhook('url-webhook', __DIR__ . '/cert.pem');
 * var_dump($bot->getWebhookInfo());
 */
