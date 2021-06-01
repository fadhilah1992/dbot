<?php 

require_once __DIR__ . '/../vendor/autoload.php';

use Fatah\Dbot\Dbot;

$bot = new Dbot('token');

$bot->on('sticker', function($update, $bot){
	$bot->sendMessage($update['message']['chat']['id'], '👙');
});

$bot->hears('/^\/start/', function($update, $bot){
	$bot->sendMessage($update['message']['chat']['id'], 'Halo Guys!');
});

$bot->launch();