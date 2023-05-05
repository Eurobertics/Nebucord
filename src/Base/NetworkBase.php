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
 * Class NetworkBase
 *
 * The base class for the HTTP client wich connects to the Discord websocket gateway.
 * Sets up some basic stuff like vars for later use and base API endpoints.
 *
 * @package Nebucord\Base
 */
class NetworkBase {

    /** @var string $_httpapiuri HTTP URI to get Discords websocket url. */
    private $_httpapiuri = "https://discord.com/api/gateway";

    /** @var string $_gatewayhost The host of the Discord gateway. */
    protected $_gatewayhost = null;

    /** @var string $_remoteapiversion The current API version of the Discord API endpoint. */
    protected $_remoteapiversion = "10";

    /**
     * @var bool $_transfercompression Are we shoud use transfer compression?
     * (zlib compression on the webserver is needed for this).
     */
    protected $_transfercompression;

    /**
     * @var string $_fullgatewayhost After building the endpoint to Discord,
     * this holds the full host incl. used protocol for connecting.
     */
    protected $_fullgatewayhost;

    /**
     * @var string $_gatewaypath Holds the path to the Discord gateway API endpoint.
     */
    protected $_gatewaypath;

    /** @var resource $_socket When connecting this holds the socket for data transfer. */
    protected $_socket = null;

    /** @var int $BUFFERSIZE On read from the socket we receive data in chunks, this is the buffer chunk size. */
    protected static $BUFFERSIZE = 128;

    /**
     * NetworkBase constructor.
     *
     * Prepares the connection and builds the API endpoint before connecting.
     *
     * @param bool $transfercompression Should we use transfer compression?
     * (zlib compression on the webserver is needed for this).
     */
    protected function __construct($transfercompression = false) {
        if(!is_bool($transfercompression)) { $this->_transfercompression = false; }
        else { $this->_transfercompression = $transfercompression; }

        $wsuri = json_decode(file_get_contents($this->_httpapiuri), true);
        $this->_gatewayhost = substr($wsuri['url'], 6);
        if($this->_gatewayhost == null || empty($this->_gatewayhost)) {
            throw new \Exception("Error getting Discord websocket API URI!");
        }

        $this->_fullgatewayhost = "ssl://".$this->_gatewayhost.":443";
        $this->_gatewaypath = "/gateway/?v=".$this->_remoteapiversion.
            "&encoding=json&compress=".$this->_transfercompression;
    }

    /**
     * NetworkBase destructor.
     *
     * Cleans up the connection by closing the socket and frees the data of the socket.
     */
    protected function __destruct() {
        if(is_resource($this->_socket)) {
            fclose($this->_socket);
        }
        $this->_socket = null;
    }
}
