<?php
/**
 *
 * Nebucord - A Discord Websocket and REST API
 *
 * Copyright (C) 2018 Bernd Robertz
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Bernd Robertz <brobertz.net@gmail.com>
 *
 */

/**
 * Basic autloader.
 *
 * Autoloads all classes and interfaces as needed.
 *
 * @param string $class The class name to be loaded.
 */
function nebucord_autoloader($class) {
    $basedir = __DIR__;

    $classname = $class;
    if(strpos($class, "\\") !== false) {
        $classname = str_replace("Nebucord\\", "", $classname);
        $classname = str_replace("\\", "/", $classname);
    }

    $class_uri = $basedir."/".$classname.".php";
    if(file_exists($class_uri)) {
        include_once $class_uri;
    }
}

spl_autoload_register('nebucord_autoloader');
