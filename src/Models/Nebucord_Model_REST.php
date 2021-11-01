<?php
/**
 *
 * Nebucord - A Discord Websocket and REST API
 *
 * Copyright (C) 2021 Bernd Robertz
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

namespace Nebucord\Models;

use \Exception;
use Nebucord\Base\Nebucord_Model_Abstract;
use Nebucord\Base\Nebucord_Status;
use Nebucord\Interfaces\Nebucord_IModelREST;
use Nebucord\REST\Base\Nebucord_RESTBase_Abstract;
use Nebucord\REST\Base\Nebucord_RESTHTTPClient;
use Nebucord\REST\Base\Nebucord_RESTBuildAPIEndpoints;

/**
 * Class Nebucord_Model_REST
 *
 * A base model for REST calls.
 *
 * @package Nebucord\Models
 */
class Nebucord_Model_REST extends Nebucord_Model implements Nebucord_IModelREST
{

    /** @var string $_status_type Holds the status type of the model (example: Nebucord_RESTStatus::REST_CREATE_MESSAGE).  */
    private $_status_type;

    /** @var string $_api_endpoint stores the current REST endpoint. */
    private $_api_endpoint;

    /** @var string $_request_type stores the HTTP request type for the REST call. */
    private $_request_type;

    /**
     * Nebucord_Model constructor.
     * Sets everything up and transfers the basic event data to the abstract class for storing.
     *
     * Instead of the mother class, this class accepts no parameter, this is due to the nature
     * of the REST call. This class is only for setting up the call and does not have an OP-code nor
     * an event name.
     */
    public function __construct(string $statustype)
    {
        parent::__construct(null, null);
        $this->_status_type = $statustype;
        $this->populate([]);
    }

    /**
     * Gets current request status type
     *
     * Returns the REST request type based on Nebucord_Status class.
     *
     * @see Nebucord_Status
     * @return string The Nebucord_Status of the current REST request.
     */
    public function getStatusType()
    {
        return $this->_status_type;
    }

    /**
     * Nebucord_Model destructor.
     *
     * Cleas everything up on end.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Sets the API endpoint for REST
     *
     * Sets the full API REST endpoint.
     *
     * @param string $endpoint The API REST endpoint.
     */
    public function setApiEndpoint(string $endpoint)
    {
        $this->_api_endpoint = $endpoint;
    }

    /**
     * Gets the API endpoint for REST
     *
     * Gets the full current REST API endpoint. Mostly used for internal
     * https requests to the gateway.
     *
     * @see Nebucord_RESTHTTPClient
     * @return string The full REST endpoint.
     */
    public function getApiEndpoint()
    {
        return $this->_api_endpoint;
    }

    /**
     * Sets the HTTP REST request type
     *
     * The https request type (POST, PUT, etc.) is set here.
     *
     * @param string $requesttype The requested request type for the API endpoint.
     * @throws \Exception If wrong or unknown type is set, an exception is thrown.
     */
    public function setRequestType(string $requesttype)
    {
        if(!in_array($requesttype, Nebucord_RESTBase_Abstract::SENDREQUEST_TYPES)) {
            throw new \Exception("Unsupported request type set: ".$requesttype.", class: Nebucord_Model_REST");
        }
        $this->_request_type = $requesttype;
    }

    /**
     * Get the REST http request type
     *
     * Mostly for internal use for the REST gateway. Gets the http request type.
     *
     * @return string The http request type.
     */
    public function getRequestType()
    {
        return $this->_request_type;
    }

    /**
     * Stores data for the model
     *
     * Stores the given array into the model. This method is derived from the parent
     * to setup specifically the REST http request type and api endpoint.
     *
     * @see Nebucord_Model_Abstract::populate()
     * @param array $data The Data to be stored in the model
     * @throws \Exception If the http request type (POST, PUT, etc.) is not known, the exception is thrown.
     */
    public function populate(array $data)
    {
        parent::populate($data);
        $this->setRequestType(Nebucord_RESTBuildAPIEndpoints::setRequestType($this->_status_type));
        $this->setApiEndpoint(Nebucord_RESTBuildAPIEndpoints::buildApiEndpoint($this->_status_type, $data));
    }
}