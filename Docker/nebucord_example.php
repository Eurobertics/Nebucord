<?php
include_once "/opt/nebucord/vendor/autoload.php";

use Nebucord\Nebucord;

// Exmaple without .ini file configuration
$n = new Nebucord(['token' => 'your-bot-token', 'ctrluser' => ['usersnowflake1', 'usersnowflake2']]);
$n->bootstrap()->run();

// Example with .ini file configuration
// Place 'nebucord.ini' in the same directory as your main (i. e. this) start PHP file.
$n = new Nebucord();
$n->bootstrap('nebucord.ini', '/var/nebucord/')->run();
