Nebucord - Discord WebSocket and REST API
=========================================


[![GitHub Release](https://img.shields.io/github/release/eurobertics/nebucord.svg?colorB=brightgreen&label=latest-stable)](https://github.com/eurobertics/nebucord)
[![GitHub Development](https://img.shields.io/badge/dev--master-v0.9.5.2-red.svg)](https://github.com/eurobertics/nebucord)
[![Github commits (since latest release)](https://img.shields.io/github/commits-since/Eurobertics/nebucord/latest.svg)](https://github.com/Eurobertics/Nebucord)
[![GitHub license](https://img.shields.io/github/license/eurobertics/nebucord.svg)](https://github.com/Eurobertics/Nebucord/blob/master/LICENSE)

This is another implementation of the Discord API. It implements an HTTP WebSocket client
as well as a REST API.  
An actual in use sample can be found on our Discord server: [Nebulatien](https://discord.gg/fVHmDD3) ![Discord](https://img.shields.io/discord/429204025678757899)  

*If you need help or have questions, don't hestiate to contact Me. Best done if you join the above mentioned
Discord server or (if you found something) create an issue in Github. Also pull request for fixes of course are
welcome.*

Of course not fully finished is it still able to do the most important things. This includes:

- Nebucord WebSocket API
    - Setup bot and run bot(s)
    - Set status for bots
    - Simple control commands
    - Callback classes to intercept Discord gateway events
    - Changing default internal command behaviours
    - Setting user id's for bot controlling
    - Configurable by parameter or by .ini file
    - Customable gateway intents
    
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
    - Many error checking
    - Way more better logging and debugging options
    - OAuth 2.0 client implementation

Default and available parameters
--------------------------------

| Parameter                  | Config name | INI config name   | Default value                   |
|----------------------------|-------------|-------------------|---------------------------------|
| Bot token                  | token       | bottoken          | (string)*empty*                 |
| ACL user snowflakes        | ctrluser    | acl               | (array)[]                       |
| WS connection retries      | wsretries   | websocket.retries | (integer)3                      |
| Default GW intent bitmask* | intents     | intents.*         | (integer)32509 / (boolean)true* |

**\*Note:**  
The intent bitmask defaults to be everything is true except `GUILD_MEMBERS` and `GUILD_PRESENCES`.
These two options has to be manual set to `true` and they have to be activated in the Bot
preferences on the Discord application management webpage.
The bitmask is `32767` if everything set to true or for config as parameter.

**Note:**  
Configuration by parameter has priority to configuration by .ini file.

Requirements
------------

Additional Composer packages are required in order to run Nebucord.  
Of course these can also be installed without Composer, but you have
to include the SPL autoloader of the packages as well.

*The recommended way is to use Composer.*

**Package informations:**
- [Connfetti-IO on Github](https://github.com/Eurobertics/Connfetti-IO)
- [Connfetti-INI on Github](https://github.com/Eurobertics/Connfetti-INI)

Install
-------

**By composer:**
```
user@linux:~# composer require eurobertics/nebucord
```
---
**By Docker (more information can be found on [Nebucord Docker Hub](https://hub.docker.com/repository/docker/eurobertics/nebucord)):**
```
docker pull eurobertics/nebucord:latest
```

**Note:**  
If you want use .ini configuration, you have to put your .ini File (`nebucord.ini` i. e.) in the mounted
directory for your Docker instance and set the path to the path of the docker working directory.
You can find more information about this on the [Nebucord Docker Hub Page](https://hub.docker.com/repository/docker/eurobertics/nebucord).

---
**By GIT:**
Simple clone this repository and use the native autoloader file in ./src Directory.

**Note:**
If you use the library without composer, you just can include
the native autoloader:

```php
include "src/nebucord_autoloader.php";
```
---

Example usage - WebSocket API
---------------------------

The WebSocket API is designed to run as a PHP CLI program.

Usage websocket API, minimalistic example:

```php
<?php
include "vendor/autoload.php";

use Nebucord\Nebucord;

$nebucord = new Nebucord(['token' => 'your_bot_token', 'ctrlusr' => ['controluser-snowflake1', 'controluser-snowflake2']]);
$nebucord->bootstrap()->run();

```

**'your_bot_token':** The auth token of your bot (from the Discord Dev-Portal)

**'controluser-snowflake':** The snowflake of Discord user(s) who can issue control commands such like 'shutdown' to the bot.

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

$nebucord = new Nebucord(['token' => 'your_bot_token', 'ctrlusr' => ['controluser-snowflake1', 'controluser-snowflake2']]);
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
