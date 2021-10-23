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

use Nebucord\Base\Nebucord_Status;

/**
 * Class Nebucord_RESTBase_Abstract
 *
 * Abstract base class for the HTTP REST client. Sets up API endpoints and some other needed stuff.
 *
 * @package Nebucord\REST\Base
 */
abstract class Nebucord_RESTBase_Abstract {

    /** @const string[] SENDREQUEST_TYPES The possible request types. */
    const SENDREQUEST_TYPES = array('GET', 'POST', 'PUT', 'PATCH');

    /** @var string $_apiurl The Discord REST API host url with protocol. */
    protected $_apiurl = "ssl://discordapp.com";

    /** @var string $_apiver The used Discord REST API version. */
    protected $_apiver = "6";

    /** @var string $_apiurl The Discord REST API host. */
    protected $_discordhost = "discordapp.com";

    /** @var string $_httpproto The transfer protocol with version. */
    protected $_httpproto = "HTTP/1.1";

    /** @var int $BUFFERSIZE The input and output buffer size chunk on transfer actions. */
    protected static $BUFFERSIZE = 128;

    /** @var resource The socket for the connection to the REST API gateway. */
    protected $_socket = 0;

    /** @var array $_header The header to be send on a REST request. */
    protected $_header;

    /** @var array $_addition_header_post If the action includes payloads, these are stored here. */
    protected $_addition_header_post;

    /** @var array $_params Userparameter like bot token, etc. . */
    protected $_params;

    /** @var string $_param_endpoint The internal var for the API endpoint. */
    protected $_param_endpoint;

    /** @var string $_param_requesttype The internal var for the API http request type. */
    protected $_param_requesttype;

    /** @var string|integer $_param_contentlength The content length of a request with payload. */
    protected $_param_contentlength;

    /** @var string $_param_json_payload The payload if requested in JSON format. */
    protected $_param_json_payload;

    /**
     * Nebucord_RESTBase_Abstract constructor.
     *
     * Sets up the base data and clears all arrays for usage.
     */
    public function __construct() {
        $this->_header = array();
        $this->_addition_header_post = array();
        $this->_params = array();
    }

    /**
     * Nebucord_RESTBase_Abstract destructor.
     *
     * Cleans up properties wich are not used any more on exit.
     */
    public function __destruct() {
    	$this->_header = array();
        $this->_addition_header_post = array();
        $this->_params = array();
    }

    /**
     * Sets up the base header.
     *
     * Prepares the base http header for sending.
     */
    public function setupBaseHeader() {
    	$this->_header = array(
            "Endpoint" => null,
            "Headerparams" => array(
                "Host:" => $this->_discordhost,
                "User-Agent:" => Nebucord_Status::CLIENTBROWSER." (".Nebucord_Status::CLIENTHOST.", ".Nebucord_Status::VERSION.")",
                "Authorization:" => "Bot ".$this->_params['token'],
                "Connection:" => "close"
            ),
            "Payload" => null
        );
        $this->_addition_header_post = array(
            "Content-Type:" => "application/json",
            "Content-Length:" => "0"
        );
    }

    /**
     * Sets REST request model for send.
     *
     * Sets the request REST model and sets it up for sending the request by storing the model properties in local
     * parameter properties.
     *
     * @param \Nebucord\Interfaces\Nebucord_IModelREST $model The model from which the parameters should be get.
     */
    protected abstract function setParams(\Nebucord\Interfaces\Nebucord_IModelREST $model);

    /**
     * Builds the request.
     *
     * After preparing the http header, this one fills the header for final sending.
     */
    protected abstract function buildRequest();

    /**
     * HTTP header to string.
     *
     * Returns the finished http header to string for sending.
     *
     * @return string The header as a string.
     */
    protected abstract function requestToString();

    /**
     * Connects to the REST gateway.
     *
     * Fires up the connection to the gateway and prepares it for sending the heeader.
     */
    protected abstract function connect();

    /**
     * Sends the header request.
     *
     * After connection to the REST gateway this sends the header data and the payload if any.
     *
     * @return integer The bytes wich are send.
     */
    protected abstract function send();

    /**
     * Receives data from the gateway.
     *
     * Reads data from the gateway send it to the responseParser and retuns the string.
     *
     * @return string The parsed repsonse.
     */
    protected abstract function receive();

    /**
     * Parses the response header.
     *
     * After receiving the response header, this method parses the header for error and returns the payload from
     * the REST gateway.
     *
     * @param string $response The full blown response header with payload.
     * @return array|null The parsed payload as array from JSON string or null on error.
     */
    protected abstract function parseResponse($response);

    /**
     * Executes the REST request.
     *
     * After everything is set up, the request is executed and if no errors occourred the method returns the response
     * from the request.
     *
     * @return string|null The payload from JSON string or null on error.
     */
    public abstract function execute();
}