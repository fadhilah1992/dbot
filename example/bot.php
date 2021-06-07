<?php

use Fatah\Dbot\Dbot;

require_once __DIR__ . '/../vendor/autoload.php';

$bot = new Dbot('token-bot');

// handle tipe update message
$bot->on('message', function($ctx){
    // variabel $ctx berisi instansi dari `Fatah\Dbot\Context` yang merupakan class yang berisi context update saat ini
    $update = $ctx->update; // array dari update saat ini
    $chat   = $update['message']['chat'];
    // bisa juga menggunakan shortcut dari Context
    $chat   = $ctx->getChat();
    $from   = $ctx->getFrom();
    
    // dapatkan id  chat dengan shortcut Context
    $chat_id = $ctx->getChat('id');
    
    // id message
    $message_id = $ctx->getMessage('message_id');
    
    // untuk mendeteksi subUpdateType dari tipe update message untuk saat ini dilakukan secara manual sbb:
    $message = $ctx->getMessage();
    
    // cek apakah ada text didalam update message
    if (isset($message['text'])) {
        // dapatkan text
	$text = $ctx->getText();
	
	if (strtolower($text) == "/start") {
	    // gunakan fitur reply dari object $ctx
	    $ctx->reply('Halo, aku dibuat dengan *Dbot*', 'Markdown');
	} elseif (strtolower($text) == "/ping") {
	    $ctx->replyWithHTML('<b>pong!!</b>');
	}
    }
});

// untuk handle subTypeUpdate dari tipe update message bisa menggunakan method on() seperti diatas
$bot->on('text', function($ctx){
    $text = strtolower($ctx->getText());
    if ($text == 'halo') {
        $ctx->replyWithMarkdown(sprintf("Halo %s, apa kabar?", $ctx->getFrom('first_name')));
    }
});


// sticker
$bot->on('sticker', function($ctx){
   // id sticker yang telah ada
   $sticker = 'CAACAgUAAxkBAAKRAWC4GpTIb_PIw8Ze_ENXackrb3slAAIbAQACksQIV05PwRXgezXdHwQ';
   $ctx->replyWithSticker($sticker);
}); 

// Deteksi Text Biasa
$bot->hears('marco', function($ctx){
	$ctx->replyWithMarkdown('*Pollo!*');
});

// Deteksi Text Regex
$bot->hears('/^\/status/i', function($ctx){
	$ctx->replyWithHTML('<i>let\'s play together!</i>');
});

// Handle Exception
$bot->hears('exception', function($ctx){
	throw new Exception("Tes handle Exception Dbot");
});


// sendPhoto dengan foto yang telah ada di server Telegram
$bot->hears('foto_id', function($ctx){
	$photo = 'AgACAgUAAxkBAAIB72C4yRAIBpu3-WQO-j1ePfrV4x8DAAJaqzEbgLTIVZZ_UejLyB5Fp96wcnQAAwEAAwIAA3MAA4UuAAIfBA';
	$ctx->replyWithPhoto($photo, 'foto id');
});

// sendPhoto dengan mengupload foto dari URL
$bot->hears('foto_url', function($ctx){
	$photo = 'https://i.pinimg.com/736x/16/02/6a/16026a38245d4f6cd1f2b3fde54bbced.jpg';
	$ctx->replyWithPhoto($photo, 'foto url');
});

// sendPhoto dengan mengupload foto dari file lokal
$bot->hears('foto_up', function($ctx){
	// upload foto
	$photo = __DIR__ . '/example-files/doraemon.jpg';
	$ctx->replyWithPhoto($photo, 'foto upload');
});

// untuk menjalankan bot secara long_polling gunakan
// untuk default parameter bisa dilihat pada `src/Dbot.php`
$bot->launch();