<?php 

use Fatah\Dbot\Dbot;

require_once __DIR__ . '/../vendor/autoload.php';

$bot = new Dbot('BOT-TOKEN');

$bot->on('text', function($ctx){
	$text = $ctx->getText();
	
	if ($text == 'p') {
		$ctx->reply('Hello *World!*', 'Markdown');
	} elseif ($text == 'pp') {
		// manual
		$ctx->telegram->sendMessage($ctx->getChat('id'), '<b>Hello World!</b>', [ 
			'parse_mode' => 'HTML'
		]);
	} elseif ($text == 'ppp') {
		// manual
		$ctx->telegram->sendMessage($ctx->update['message']['chat']['id'], 'Hello World!', [
			'reply_to_message_id' => $ctx->getMessage('id')
		]);
	} elseif ($text == 'sticker') {
		$sticker = 'CAACAgUAAxkBAAKRAWC4GpTIb_PIw8Ze_ENXackrb3slAAIbAQACksQIV05PwRXgezXdHwQ';
		$ctx->replyWithSticker($sticker);
	}
});

$bot->on('sticker', function($ctx){
	// id sticker yang telah ada
	$sticker = 'CAACAgUAAxkBAAKRAWC4GpTIb_PIw8Ze_ENXackrb3slAAIbAQACksQIV05PwRXgezXdHwQ';
	$ctx->replyWithSticker($sticker);
});

// Text Biasa
$bot->hears('halo', function($ctx){
	$ctx->replyWithMarkdown('*Hayy!*');
});

// Regex
$bot->hears('/^\/start/i', function($ctx){
	$ctx->replyWithHTML('<i>let\'s play together!</i>');
});

// Handle Exception
$bot->hears('exception', function($ctx){
	throw new Exception("Tes handle Exception Dbot");
});


// sendPhoto
$bot->hears('foto_id', function($ctx){
	$photo = 'AgACAgUAAxkBAAIB72C4yRAIBpu3-WQO-j1ePfrV4x8DAAJaqzEbgLTIVZZ_UejLyB5Fp96wcnQAAwEAAwIAA3MAA4UuAAIfBA';
	$ctx->replyWithPhoto($photo, 'foto id');
});

// sendPhoto
$bot->hears('foto_url', function($ctx){
	$photo = 'https://i.pinimg.com/736x/16/02/6a/16026a38245d4f6cd1f2b3fde54bbced.jpg';
	$ctx->replyWithPhoto($photo, 'foto url');
});

// sendPhoto
$bot->hears('foto_up', function($ctx){
	// upload foto
	$photo = __DIR__ . '/doraemon.jpg';
	$ctx->replyWithPhoto($photo, 'foto upload');
});


$bot->launch(false, [ 
	'webhook_log' => __DIR__ . '/webhook_log.txt'
]);