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
use Nebucord\Base\AbstractModel;
use Nebucord\Base\StatusList;
use Nebucord\Interfaces\IModelREST;
use Nebucord\REST\Base\AbstractBase;
use Nebucord\REST\Base\HttpClient;
use Nebucord\REST\Base\BuildApiEndpoints;

/**
 * Class ModelREST
 *
 * A base model for REST calls.
 *
 * @package Nebucord\Models
 */
class ModelREST extends Model implements IModelREST
{

    /** @var string $_status_type Holds the status type of the model (example: RestStatusList::REST_CREATE_MESSAGE).  */
    private $_status_type;

    /** @var string $_api_endpoint stores the current REST endpoint. */
    private $_api_endpoint;

    /** @var string $_request_type stores the HTTP request type for the REST call. */
    private $_request_type;

    /**
     * Model constructor.
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
     * Returns the REST request type based on StatusList class.
     *
     * @return string The StatusList of the current REST request.
     *@see StatusList
     */
    public function getStatusType()
    {
        return $this->_status_type;
    }

    /**
     * Model destructor.
     *
     * Cleas everything up on end.
     */
    public function __destruct()
    {
        parent::__destruct();
        $this->_status_type = "";
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
     * @return string The full REST endpoint.
     *@see HttpClient
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
        if(!in_array($requesttype, AbstractBase::SENDREQUEST_TYPES)) {
            throw new \Exception("Unsupported request type set: ".$requesttype.", class: ModelREST");
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
     * @param array $data The Data to be stored in the model
     * @throws \Exception If the http request type (POST, PUT, etc.) is not known, the exception is thrown.
     *@see AbstractModel::populate()
     */
    public function populate(array $data)
    {
        parent::populate($data);
        $this->setRequestType(BuildApiEndpoints::setRequestType($this->_status_type));
        $this->setApiEndpoint(BuildApiEndpoints::buildApiEndpoint($this->_status_type, $data));
    }
}
