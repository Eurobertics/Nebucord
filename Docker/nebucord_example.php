<?php
include "/opt/nebucord/vendor/autoload.php";

use Nebucord\Nebucord;

$n = new Nebucord(['token' => 'your-bot-token', 'ctrluser' => ['usersnowflake1', 'usersnowflake2']]);
$n->bootstrap()->run();
