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

use Nebucord\Base\Nebucord_Status;
use Nebucord\Base\Nebucord_Timer;

/**
 * Class Nebucord_WebSocket
 *
 * The websocket client after the initial HTTP connection and a successful connection upgrade.
 * This class reads and writes all the data given by Nebucord and does all the framing and extraction based
 * on the websocket protocol (RFC 6455).
 *
 * @package Nebucord\Http
 */
class Nebucord_WebSocket extends Nebucord_Http_Client {

    /** @var object $_instance The websocket instance. */
    private static $_instance;

    /** @var int $requestcount The amount of request within the RATELIMIT_TIMEFRAME (resets after RATELIMIT_TIMEFRAME timed out). */
    private $requestcount = 0;

    /** @var Nebucord_Timer $ratelimit_timer The timer wich is used to determine rate limit. */
    private $ratelimit_timer;

    /**
     * Creates an websocket instance.
     *
     * The connection should only be once per Nebucord instance. So this is the creation of a singleton websocket.
     *
     * @return Nebucord_WebSocket|object The created websocket instance.
     */
    public static function getInstance() {
        \Nebucord\Logging\Nebucord_Logger::info("Starting HTTP client...");
        if(self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    /**
     * Deletes a websocket instance.
     *
     * On exit it is necessary to delete the websocket instance. This is done here.
     *
     * @param Nebucord_WebSocket $instance The instance to delete.
     */
    public static function destroyInstance($instance) {
        if($instance instanceof Nebucord_WebSocket) {
            unset($instance);
        }
    }

    /**
     * Nebucord_WebSocket constructor.
     *
     * Sets everything up.
     */
    protected function __construct() {
        parent::__construct();
        $this->ratelimit_timer = new Nebucord_Timer();
        $this->ratelimit_timer->startTimer();
    }

    /**
     * Nebucord_WebSocket destructor.
     *
     * Cleans everything up.
     */
    public function __destruct() {
        parent::__destruct();
    }

    /**
     * Nebucord_Websocket clone.
     *
     * Due to singleton, set to private and not used.
     */
    private function __clone() {
    }

    /**
     * The Frames a mesasge send to the gateway.
     *
     * The websocket protocol (RFC 6455) write down that every message send, has to be framed with information
     * about the message. This method does it.
     *
     * @param string $message The message to be framed.
     * @param string $type The message type, so it can be correct framed (in this case mostly text).
     * @param bool $masked Should the message be binary masked on sending?
     * @return string The (maybe) masked and framed message in once.
     */
    private function wsEncode($message, $type = 'text', $masked = true) {
        $frameheader = array();
        $mask = "";
        $length = strlen($message);

        switch ($type) {
            case 'text' :
                // first byte indicates FIN, Text-Frame (10000001):
                $frameheader[0] = 129;
                break;
            case 'close' :
                // first byte indicates FIN, Close Frame(10001000):
                $frameheader[0] = 136;
                break;
            case 'ping' :
                // first byte indicates FIN, Ping frame (10001001):
                $frameheader[0] = 137;
                break;
            case 'pong' :
                // first byte indicates FIN, Pong frame (10001010):
                $frameheader[0] = 138;
                break;
        }

        // set mask and payload length (using 1, 3 or 9 bytes)
        if ($length>65535) {
            $binlength = str_split(sprintf('%064b', $length), 8);
            $frameheader[1] = ($masked===true) ? 255 : 127;
            for ($i = 0; $i<8; $i++) {
                $frameheader[$i + 2] = bindec($binlength[$i]);
            }
            // most significant bit MUST be 0 (close connection if frame too big)
            if ($frameheader[2]>127) {
                //$this->close(1004);
                return false;
            }
        } elseif ($length>125) {
            $binlength = str_split(sprintf('%016b', $length), 8);
            $frameheader[1] = ($masked===true) ? 254 : 126;
            $frameheader[2] = bindec($binlength[0]);
            $frameheader[3] = bindec($binlength[1]);
        } else {
            $frameheader[1] = ($masked===true) ? $length + 128 : $length;
        }

        // convert frame-head to string:
        foreach (array_keys($frameheader) as $i) {
            $frameheader[$i] = chr($frameheader[$i]);
        }

        if ($masked===true) {
            // generate a random mask:
            $mask = array();
            for ($i = 0; $i<4; $i++)
                $mask[$i] = chr(rand(0, 255));
            $frameheader = array_merge($frameheader, $mask);
        }

        $encmsg = implode('', $frameheader);

        // append payload to frame:
        for ($i = 0; $i<$length; $i++) {
            $encmsg .= ($masked === true) ? $message[$i] ^ $mask[$i % 4] : $message[$i];
        }

        return $encmsg;
    }

    /**
     * Decodes a message received by the gateway.
     *
     * As for sending, Nebucord needs to unframe (and unmask if so) the message and reads the information from the
     * message header as stated in RFC 6455.
     * After that, the message is returned in clear text.
     *
     * @param string $bytes The readed encoded string from the gateway (maybe a byte string).
     * @param integer &$payloadlen Length of the payload after decoding the byte string.
     * @param integer &$wsopcode The WS OP code from the gateway.
     * @return string The decoded message as a string.
     */
    private function wsDecode($bytes, &$payloadlen, &$wsopcode) {
        $payloadlen = 0;
        $decodeddata = "";

        $wsopcode = ord($bytes[0]) & 0x0f;
        $firstByte = "0x".sprintf('%02.x', ord($bytes[0]));
        if($firstByte != "0x81") {
            $code = unpack('n', substr($bytes, 2, 4))[1];
            $message = substr($bytes, 4);
            return $code." ".$message;
        }
        $secondByte = sprintf('%08b', ord($bytes[1]));
        $masked = ($secondByte[0]=='1') ? true : false;
        $length = ($masked===true) ? ord($bytes[1]) & 127 : ord($bytes[1]);

        if ($masked===true) {
            if ($length===126) {
                $mask = substr($bytes, 4, 4);
                $codeddata = substr($bytes, 8);
            } elseif ($length===127) {
                $mask = substr($bytes, 10, 4);
                $codeddata = substr($bytes, 14);
            } else {
                $mask = substr($bytes, 2, 4);
                $codeddata = substr($bytes, 6);
            }

            for ($i = 0; $i<strlen($codeddata); $i++) {
                $decodeddata .= $codeddata[$i] ^ $mask[$i % 4];
            }

        } else {
            if ($length===126) {
                $decodeddata = substr($bytes, 4);
                $pll_tmp = unpack("n", substr($bytes, 2, 2));
                $payloadlen = $pll_tmp[1];
            }
            elseif ($length===127) {
                $decodeddata = substr($bytes, 10);
                $pll_tmp = unpack("J", substr($bytes, 2, 8));
                $payloadlen = $pll_tmp[1];
            }
            else {
                $decodeddata = substr($bytes, 2);
                $payloadlen = $length;
            }
        }

        return $decodeddata;
    }

    /**
     * Reads all data from the gateway.
     *
     * After decoding the first buffer chunk, the payload length from the gateway is known though the frame header
     * of the message. So this method reads all the data for the given length of the payload.
     *
     * @return array First key: the decoded byte string in JSON format, second key: 0 on no error, -1 on failure or -2 on gateway closes connection.
     */
    public function soReadAll() {
        $pl_len = 0;
        $ps_len = self::$BUFFERSIZE;
        $rectext = $buf = "";
        $wsopcode = 0;
        $buf = fread($this->_socket, self::$BUFFERSIZE);
        if($buf === false) { return [-1]; }
        if(strlen($buf) > 0) {
            $buf = $this->wsDecode($buf, $pl_len, $wsopcode);
            if($wsopcode == 8) { return [-2, $buf]; }
            $pl_len -= strlen($buf);
            $rectext .= $buf;
            while($pl_len > 0) {
                if($pl_len < self::$BUFFERSIZE) { $ps_len = $pl_len; }
                $buf = fread($this->_socket, $ps_len);
                if($buf === false) { return [-1]; }
                $pl_len -= strlen($buf);
                $rectext .= $buf;
            }
        }
        return [0, $rectext];
    }

    /**
     * Sends all data to the gateway.
     *
     * After endoding the data to be send with JSON, this method determine the length, frames it and sends it
     * to the gateway.
     *
     * @param string $data The JSON encoded string to be send.
     * @return integer The length which was send or -1 on error.
     */
    public function soWriteAll($data) {
        if($this->ratelimit_timer->getTime() > Nebucord_Status::RATELIMIT_TIMEFRAME) {
            $this->ratelimit_timer->reStartTimer();
            $this->requestcount = 0;
        }
        if($this->requestcount >= Nebucord_Status::RATELIMIT_MAXREQUEST) {
            \Nebucord\Logging\Nebucord_Logger::warn("Request dropped due to rate limit!");
            return 0;
        }
        $encdata = $this->wsEncode($data);
        if(!$encdata) { return -1; }
        $length = strlen($encdata);
        $sendbytes = 0;
        while($sendbytes != $length) {
            $bytes = fwrite($this->_socket, $encdata, $length);
            if($bytes === false || $bytes == 0) {
                $sendbytes = -1;
                break;
            }
            $sendbytes += $bytes;
        }
        $this->requestcount++;
        return $sendbytes;
    }
}