Nebucord - Discord WebSocket and REST API
=========================================


[![GitHub Release](https://img.shields.io/github/release/eurobertics/nebucord.svg?colorB=brightgreen&label=latest-stable)](https://github.com/eurobertics/nebucord)
[![GitHub Development](https://img.shields.io/badge/dev--master-v0.7.0-red.svg)](https://github.com/eurobertics/nebucord)
[![Github commits (since latest release)](https://img.shields.io/github/commits-since/Eurobertics/nebucord/latest.svg)](https://github.com/Eurobertics/Nebucord)
[![GitHub license](https://img.shields.io/github/license/eurobertics/nebucord.svg)](https://github.com/Eurobertics/Nebucord/blob/master/LICENSE)

This another implementation of the Discord API. It implements an HTTP WebSocket client
as well as a REST API.  
Of course not fully funished is it still able to do the most important things. This includes:

- Nebucord WebSocket API
    - Setup bot and run bot(s)
    - Set status for bots
    - Simple control commands
    - Callback classes to intercept Discord gateway events
    - Changing default internal command behaviours
    - Setting user id's for bot controlling
    
- Nebucord REST API
    - Model oriented interface on data receiving
    - Sending messages to a channel or DM
    - Receiving roles
    - Receiving message(s)
    - Receiving guild members
    - many more...

- What's missing
    - Some models for events
    - Some models and methods for interacting with REST
    - Some gateway events
    - Automatically reconnecting
    - Many error checking
    - Way more better logging and debugging options
    - Some WebSocket frame OP codes for message control
    - OAuth 2.0 client implementation

Install
-------

By composer:
```
user@linux:~# composer require eurobertics/nebucord
```

By GIT:
Simple clone this repository and use the native autoloader file in ./src Directory.

---
**Note:**
If you use the library without composer, you just can include
the native autoloader:

```php
include "src/nebucord_autoloader.php";
```
---

Example usage - WinSock API
---------------------------

The WinSock API is designed to run as a PHP CLI program.

Usage websocket API, minimalistic example:

```php
<?php
include "vendor/autoload.php";

use Nebucord\Nebucord;

$nebucord = new Nebucord(['token' => 'your_bot_token']);
$nebucord->bootstrap()->run();

```

Of course this only starts the bot and this one sits there and does nothing.

A more complex example:

```php
<?php
include "vendor/autoload.php";

use Nebucord\Nebucord;

class MessageInterceptorClass {
    public function onMessageReceive($evt) {
        // $evt is a model with all data from the gateway if a message create
        // event is received
        
        echo $evt->content; // returns the message
    }
}

$nebucordEventTable = \Nebucord\Events\Nebucord_EventTable::create();
$nebucordEventTable->addEvent(new MessageInterceptorClass, "onMessageReceive", \Nebucord\Base\Nebucord_Status::GWEVT_MESSAGE_CREATE);

$nebucord = new Nebucord(['token' => 'your_bot_token']);
$nebucord->bootstrap()
    ->setEventTable($nebucordEventTable)
    ->run();
```

The above example prints out every message which was seen by the bot (based on the bot
roles on the Discord guild). "Seen" means all CREATE_MESSAGE events from the Discord
gateway.

Now on a console:

```
user@linux:~# php -f <your_php_file>.php
```

 Example usage - REST API
 ---------------------------
 
Basic usage for sending a message:
 
 ```php
<?php
include "vendor/autoload.php";

use Nebucord\NebucordREST;

$nebucordREST = new NebucordREST(['token' => 'your_bot_token']);
$message_model = $nebucordREST->channel->createMessage(123123123123 /* channel id */, "message");
```

"$message_model" is an object with the answer from the REST gateway.

Basic usage for receiving guild channels:

Basic usage for sending a message:
 
 ```php
<?php
include "vendor/autoload.php";

use Nebucord\NebucordREST;

$nebucordREST = new NebucordREST(['token' => 'your_bot_token']);
$channels = $nebucordREST->guild->getGuildChannels(123123123123123 /* guild id*/);
```

"$channels" is an array of channel models for processing.

More info
---------

For more information see: [Discord Developer Portal](https://discordapp.com/developers/docs/intro)
