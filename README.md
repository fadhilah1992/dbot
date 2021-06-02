<header>
	<img src="header.png" style="background: #FFFFFF3F">
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
[fatah@home]:~$ vim test/bot.php
[fatah@home]:~$ php test/bot.php
```

## Source
`test/bot.php`
```php
require_once __DIR__ . '/../vendor/autoload.php';

use Fatah\Dbot\Dbot;

$bot = new Dbot('token');

$bot->on('message', function($update, $bot){
  // do more
});

$bot->on('sticker', function($update, $bot){
	$bot->sendMessage($update['message']['chat']['id'], '👙');
});

$bot->hears('/^\/start/', function($update, $bot){
	$bot->sendMessage($update['message']['chat']['id'], 'Halo Guys!');
});

$bot->launch();
```

## Kontribusi
Dipersilahkan :)