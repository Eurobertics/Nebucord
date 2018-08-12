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

namespace Nebucord\Base;

/**
 * Class Nebucord_Controller_Abstract
 *
 * Abstract base controller class for the Nebucord controller classes.
 *
 * @package Nebucord\Base
 */
abstract class Nebucord_Controller_Abstract {

    /**
     * Nebucord_Controller_Abstract constructor.
     *
     * Constructor to be used for inherited classes. Currently empty.
     */
    public function __construct() {
    }

    /**
     * Nebucord_Controller_Abstract constructor.
     *
     * Constructor to be used for inherited classes. Currently empty.
     */
    public function __destruct() {
    }

    /**
     * Parses JSON Strings
     *
     * A (very) simple wrapper wich returns an array from a JSON String.
     * Regularly used by the controllers.
     *
     * @param string $jsonmsg The JSON string to decode.
     * @return array|null Returns the array or null if JSON is invalid.
     */
    public function parseJSON($jsonmsg) {
        if(strlen($jsonmsg) > 0) {
            $message = json_decode($jsonmsg, true);
            return $message;
        } else {
            return null;
        }
    }

    /**
     * Converts an array to a JSON string.
     *
     * Currently only a (very) simple wrapper used by the controllers to convert an array
     * to a JSON string.
     *
     * @param array $message_ar The array to be encoded.
     * @return string|null Retuns a JSON string or null on error.
     */
    public function prepareJSON($message_ar) {
        if(count($message_ar) > 0) {
            return json_encode($message_ar);
        } else {
            return null;
        }
    }
}
