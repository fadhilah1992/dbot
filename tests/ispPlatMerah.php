<?php 

require_once __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Promise;

$token 	= 'TOKEN-BOT';
$chatid = 'yourid';
$photo 	= 'https://i.pinimg.com/736x/16/02/6a/16026a38245d4f6cd1f2b3fde54bbced.jpg';

$client = new Client([
	'base_uri' => 'https://api.telegram.org/bot' . $token . '/'
]);


$promises = [

];

$client->request('GET', 'sendPhoto', [ 
	'query' => [ 
		'chat_id' => $chatid,
		'photo' => $photo
	]
]);