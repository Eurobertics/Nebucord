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

namespace Nebucord\REST\Base;

use Nebucord\Interfaces\Nebucord_IModelREST;

/**
 * Class Nebucord_RESTHTTPClient
 *
 * The HTTP REST client, wich prepares, sends and read data from the Discord REST gateway.
 *
 * @package Nebucord\REST\Base
 */
class Nebucord_RESTHTTPClient extends Nebucord_RESTBase_Abstract {

    /**
     * Nebucord_RESTHTTPClient constructor.
     *
     * Sets up the http client and sets user parameters.
     *
     * @param array $params The given user params like bot token.
     */
    public function __construct(array $params = array()) {
    	parent::__construct();
    	$this->_params = $params;
    }

    /**
     * Nebucord_RESTHTTPClient destructor.
     *
     * Closes the connection on finish and cleas up the socket.
     */
    public function __destruct() {
    	parent::__destruct();
    	if(is_resource($this->_socket)) {
            fclose($this->_socket);
        }
    }

    /**
     * Sets REST request model for send.
     *
     * @see Nebucord_RESTBase_Abstract::setParams()
     *
     * @param Nebucord_IModelREST $model
     */
    public function setParams(Nebucord_IModelREST $model) {
        $json_payload = json_encode($model->toArray());
        $this->_param_endpoint = $model->getApiEndpoint();
        $this->_param_requesttype = $model->getRequestType();
        $this->_param_contentlength = strlen($json_payload);
        $this->_param_json_payload = $json_payload;
    }

    /**
     * Builds the request.
     *
     * @see Nebucord_RESTBase_Abstract::buildRequest()
     */
    protected function buildRequest() {
        $this->_header['Endpoint'] = "/api/v".$this->_apiver.$this->_param_endpoint;
        if($this->_param_requesttype == "POST") {
            $this->_header['Headerparams'] += $this->_addition_header_post;
            $this->_header['Headerparams']['Content-Length:'] = $this->_param_contentlength;
            $this->_header['Payload'] = $this->_param_json_payload;
        }
    }

    /**
     * HTTP header to string.
     *
     * @see Nebucord_RESTBase_Abstract::requestToString()
     *
     * @return string
     */
    protected function requestToString() {
        $header = $this->_param_requesttype." ".$this->_header['Endpoint']." ".$this->_httpproto."\r\n";
        foreach($this->_header['Headerparams'] as $hfield => $hval) {
            $header .= $hfield." ".$hval."\r\n";
        }
        $header .= "\r\n";
        $header .= $this->_header['Payload'];
        return $header;
    }

    /**
     * Connects to the REST gateway.
     *
     * @see Nebucord_RESTBase_Abstract::connect()
     */
    protected function connect() {
        $socket = fsockopen($this->_apiurl, 443, $errno, $errstr, 10);
        $this->_socket = $socket;
    }

    /**
     * Sends the header request.
     *
     * @see Nebucord_RESTBase_Abstract::send()
     * @return integer
     */
    protected function send() {
        $this->buildRequest();
        $request = $this->requestToString();
        $bytes = fwrite($this->_socket, $request, strlen($request));
        return $bytes;
    }

    /**
     * Receives data from the gateway.
     *
     * @see Nebucord_RESTBase_Abstract::receive()
     *
     * @return array Of the received header.
     */
    protected function receive() {
        $recdata = $buffer = fread($this->_socket, self::$BUFFERSIZE);
        while(strlen($buffer) > 0) {
            $buffer = fread($this->_socket, self::$BUFFERSIZE);
            $recdata .= $buffer;
        }
        return $this->parseResponse($recdata);
    }

    /**
     * Parses the response header.
     *
     * @see Nebucord_RESTBase_Abstract::parseResponse()
     *
     * @param string $response
     * @return array|null
     */
    protected function parseResponse($response) {
        $res = null;
        $res_ar = explode("\r\n\r\n", $response);
        if(strpos($res_ar[0], "200 OK") !== false) {
            $r = trim(substr($res_ar[1], 4, -3));
            $res = json_decode(preg_replace('/[[:cntrl:]]/', '', $r), true);
        }
        return $res;
    }

    /**
     * Executes the REST request.
     *
     * @see Nebucord_RESTBase_Abstract::execute()
     *
     * @return array|null
     */
    public function execute() {
        $this->connect();
        $bytes = $this->send();
        if($bytes > 0) {
            return $this->receive();
        }
        return null;
    }
}
