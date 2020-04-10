<?php
if($argc == 1) {
    echo "\n\nThis is the default Nebucord entrypoint wich is empty. Please mount (-v / --volume)\n";
    echo "the directory '/var/nebucord' and store your Nebucord classes in this directory.\n";
    echo "Then run docker with the main file you created for Nebucord, i. e:\n\n";
    echo "'docker run -it --rm -v \${pwd}/nebucordclasses:/var/nebucord eurobertics/nebucord:tag yourfile.php'\n\n";
} else {
    include '/var/nebucord/' .$argv[1];
}