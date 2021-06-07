<header>
	<img src="docs/header.png" style="background: #FFFFFF3F">
</header>

## Requirements
- PHP ^8.0 || ^7.3
- Telegram Bot
- Git (Optional) 

## Install & Test

```bash
[fatah@home]:~$ cd ~/Code
[fatah@home]:~$ git clone https://github.com/fathurrohman26/dbot.git Dbot
[fatah@home]:~$ cd Dbot
[fatah@home]:~$ composer update -vv
[fatah@home]:~$ # sesuakan token bot
[fatah@home]:~$ vim example/bot.php
[fatah@home]:~$ php example/bot.php
```

## Source
`example/bot.php`
```php
<?php 

use Fatah\Dbot\Dbot;

require_once __DIR__ . '/../vendor/autoload.php';

$bot = new Dbot('TOKEN-BOT');

$bot->on('text', function($ctx){
	$text = $ctx->getText();
	// $text = $ctx->getMessage('text')
	// $text = $ctx->update['message']['text'];
	
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

$bot->launch();
```

## Catatan
Untuk method telegram yang belum ada, bisa menggunakan default method Telegram `$this->request('namaMethod', [ 'parameter' => 'isi_paramter' ])` jika pada callback Context maka gunakan `$ctx->telegram->request('namaMethod', [ 'parameter' => 'isi_paramter' ])` 
Bisa juga dengan membuatnya langsung pada class Telegram `src/Telegram.php` dan lakukan pull request hehe :), Mari kembangkan bersama bersama :)

## Credits
- fatah 
- fadhilah1992
-
-
- 

## Kontribusi
Dipersilahkan :)
