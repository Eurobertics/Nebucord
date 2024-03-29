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

namespace Nebucord\Http;

use Nebucord\Base\NetworkBase;
use Nebucord\Base\Timer;

/**
 * Class HttpClient
 *
 * The basic http client for inital connect to the Discord gateway. After successfully connected, it upgrades
 * the HTTP client to a websocket client.
 *
 * @package Nebucord\Http
 */
class HttpClient extends NetworkBase {

    /** @var resource $_socket The socket connection ressource for transmitting and receiving data from the gateway. */
    protected $_socket;

    /**
     * HttpClient constructor.
     *
     * Sets itself up and gets ready for connection.
     */
    protected function __construct() {
        parent::__construct();
        $this->_socket = 0;
    }

    /**
     * HttpClient destructor.
     *
     * Shutds itself down and cleans up the socket.
     */
    protected function __destruct() {
        parent::__destruct();
        if(is_resource($this->_socket)) {
            fclose($this->_socket);
        }
        $this->_socket = 0;
        \Nebucord\Logging\MainLogger::info("Socket closed...");
    }

    /**
     * Genreates a valic websocket key.
     *
     * The websocket request a key from the client for connection upgrade. This method creates such a key.
     *
     * @return string The key in base64 format.
     */
    private function generateWSKey() {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!"$&/()=[]{}0123456789';
        $key = '';
        $chars_length = strlen($chars);
        for ($i = 0; $i < 16; $i++) {
            $key .= $chars[random_int(0, $chars_length-1)];
        }
        return base64_encode($key);
    }

    /**
     * Creates a HTTP client header.
     *
     * Creates the basic HTTP header for websocket connection upgrade.
     *
     * @return string The GET HTTP header for websocket connection upgrade.
     */
    private function createWSHeader() {
        return "GET ".$this->_gatewaypath." HTTP/1.1\r\n".
            "Upgrade: websocket\r\n".
            "Connection: Upgrade\r\n".
            "Host: ".$this->_gatewayhost."\r\n".
            "Origin: https://".$this->_gatewayhost."\r\n".
            "Sec-WebSocket-Key: ".$this->generateWSKey()."\r\n".
            "Sec-WebSocket-Version: 13\r\n\r\n";
    }

    /**
     * Sends header for initial connection.
     *
     * Sends builded header for inital commit and awaits response.
     *
     * @return bool On success true, otherwise false, or -1 if socket response with error.
     */
    private function sendHeader() {
        \Nebucord\Logging\MainLogger::info("Connecting to Discord WebSocket gateway...");
        $retheader = "";
        $header = $this->createWSHeader();
        $timer = new Timer();
        $socket = stream_socket_client($this->_fullgatewayhost);
        if(!$socket) {
            return -1;
        }
        stream_set_blocking($socket, false);
        fwrite($socket, $header, strlen($header));
        $timer->startTimer();
        while($timer->getTime() < 10000) {
            if(!$socket) {
                return -1;
            }
            $timer->reStartTimer();
            $retheader .= fread($socket, 2048);
            if(strlen($retheader) > 0) { break; }
        }
        unset($timer);
        if($this->checkReturnHeader($retheader)) {
            $this->_socket = $socket;
            $retstate = true;
        } else {
            $this->_socket = -1;
            $retstate = false;
        }
        return $retstate;
    }
    
    /**
     * Connects to the reconnect Webosocket
     * 
     * Uses the new written _fullgatewayhost to connect to the Websocket
     * without sending HTTP header since the reconnect procedure does not need
     * to reconnect with new WS upgrade.
     * 
     * @return int On success 1, otherwise -1 if socket response with error.
     */
    public function connectToWSURL()
    {
        $socket = stream_socket_client($this->_fullgatewayhost);
        if(!$socket) {
            return -1;
        }
        stream_set_blocking($socket, false);
        $this->_socket = $socket;
        return 1;
    }

    /**
     * Checks the returned data of the inital connection.
     *
     * After sending the header the method inspects the response for a valid connection upgrade to websockets.
     *
     * @param string $retheader The returned data preferable a valid header from the Discord gateway.
     * @return bool true if connection is successful otherwise false.
     */
    private function checkReturnHeader($retheader) {
        $header_ar = array();
        $splitheader = explode("\r\n", $retheader);
        $header_ar['Response'] = $splitheader[0];
        for($i = 1; $i < count($splitheader); $i++) {
            if($splitheader[0] == "" && $splitheader[1] == "") { continue; }
            $splitheaderrow = explode(":", $splitheader[$i]);
            $header_ar[trim($splitheaderrow[0])] = (isset($splitheaderrow[1])) ? trim($splitheaderrow[1]) : '';
        }
        if($header_ar['Connection'] == 'upgrade') {
            return true;
        }
        return false;
    }

    /**
     * Connects to the Discord gateway.
     *
     * Sends the builded header to the gateway and checks for response by other methods within this class.
     *
     * @return bool If connection is established true, false on error.
     */
    public function connect() {
        if(!$this->sendHeader()) {
            \Nebucord\Logging\MainLogger::error("Connection can't established, HTTP response wrong, exiting...");
            return false;
        }
        \Nebucord\Logging\MainLogger::infoImportant("WebSocket connection established.");
        return true;
    }

    public function reconnect($full = false) {
        fclose($this->_socket);
        if($full) {
            if ($this->connectToWSURL()) {
                \Nebucord\Logging\MainLogger::info("Trying to get missing events, sending resume request...");
                return true;
            }
        } else {
            if ($this->connect()) {
                \Nebucord\Logging\MainLogger::info(
                    "Trying to get missing events, sending resume request after full reconnect..."
                );
                return true;
            }
        }
        return false;
    }

    public function setNewWSConnectURL($reconnectwsurl)
    {
        $this->_gatewayhost = substr($reconnectwsurl, 6);
        $this->_fullgatewayhost = "ssl://".$this->_gatewayhost.":443";
    }
}
