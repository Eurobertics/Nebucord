 ## Supported tags and respective ```Dockerfile``` links

Actually there is only ```nebucord:latest``` wich represents the
latest stable release of Nebucord.

**Current version:** [![GitHub Release](https://img.shields.io/github/release/eurobertics/nebucord.svg?colorB=brightgreen&label=latest-stable)](https://github.com/eurobertics/nebucord)

## Quick reference

**GitHub project:** [https://github.com/Eurobertics/Nebucord](https://github.com/Eurobertics/Nebucord)  
**Packagist:** [https://packagist.org/packages/eurobertics/nebucord](https://packagist.org/packages/eurobertics/nebucord)  
**Discord:** [Nebulatien #bot-werkstatt-💡](https://discord.gg/fVHmDD3) ![Discord](https://img.shields.io/discord/429204025678757899)  
**Docker base image:** [php:7.4-cli](https://hub.docker.com/_/php)

## What is Nebucord

```Nebucord``` is another implementation of a [```Discord```](https://discordapp.com) bot API.
It implements a WebSocket for realtime comunication and is extendable for intercepting
events on your own [```Discord```](https://discordapp.com) server.

For more information see [Nebucord GitHub README.md](https://github.com/Eurobertics/Nebucord/blob/master/README.md)

#### Basic usage and exmaple code for instantiating```Nebucord```

First you have to create an instance of Nebucord. The minimalistic code would be like:

```php
<?php
include "/opt/nebucord/vendor/autoload.php";

use Nebucord\Nebucord;

$nebucord = new Nebucord(['token' => 'your_bot_token', 'ctrlusr' => ['controluser-snowflake1', 'controluser-snowflake2']]);
$nebucord->bootstrap()->run();
```

More example can be found on the ```GitHub project page```.  
This includes an example Nebucord start file here: [Github Nebucord Docker example startup file](https://github.com/Eurobertics/Nebucord/blob/master/Docker/nebucord_example.php)

#### Configuration INI file setup (as of v0.9.5.2)

To use configuration INI files, you have to make the configuration file (`nebucord.ini` i. e.) visible for the Nebucord Docker image.  
This is done by placing the .ini file within the Nebucord main class instance. The you have to set the .ini file path in the Nebucord bootstrapper
to the working directory of Docker instance. In simple example is as follows:

```php
<?php
include "/opt/nebucord/vendor/autoload.php";

use Nebucord\Nebucord;

$nebucord = new Nebucord();
$nebucord->bootstrap('nebucord.ini', '/var/nebucord/')->run();
```


#### Howto use this image

This image is intended to use without a ```Dockerfile```. You can just pull the image
and run it. It has an own entrypoint.

After you created your code, you can run ```Nebucord``` by executing the following example command:

```docker run -it --rm -v ${pwd}/yournebucordcode:/var/nebucord eurobertics/nebucord:latest yourmainnebucrodfile.php```

You should see the log output of ```Nebucord```.

## Licence

This software is licensed under ```GNU General Public License v3.0``` wich can be found in
the [license file of the project](https://github.com/Eurobertics/Nebucord/blob/master/LICENSE).

As with all Docker images, these likely also contain other software which may be under other
licenses (such as Bash, etc from the base distribution, along with any direct or indirect
dependencies of the primary software being contained).

As for any pre-built image usage, it is the image user's responsibility to ensure that any use
of this image complies with any relevant licenses for all software contained within.