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

namespace Nebucord\REST\Action;

use Nebucord\Base\Nebucord_Status;
use Nebucord\Factories\Nebucord_Model_Factory;
use Nebucord\Models\Nebucord_Model;
use Nebucord\Models\Nebucord_Model_REST;
use Nebucord\REST\Base\Nebucord_RESTAction;
use Nebucord\REST\Base\Nebucord_RESTHTTPClient;

/**
 * Class Nebucord_RESTExecutor
 *
 * Creates and executes all kinds of REST requests to the API gateway.
 * It just needs a REST type (from type Nebucord_Status) and a prepared model
 * (from type Nebucord_Model).
 *
 * @see Nebucord_Status
 * @see Nebucord_Model
 * @package Nebucord\REST\Action
 */
class Nebucord_RESTExecutor extends Nebucord_RESTAction
{
    /** @var Nebucord_Model|array|null $returnmodel Holds the model data on REST request return (maybe as a array of data) or null if empty/error. */
    private $returnmodel = null;

    /**
     * Nebucord_RESTExecutor constructor
     *
     * Sets up the HTTP client for the request and gather ressource associated with it.
     *
     * @param $httpclient Nebucord_RESTHTTPClient HTTP client to perform the REST request.
     */
    public function __construct(&$httpclient) {
        parent::__construct();
        $this->_httpclient = $httpclient;
    }

    /**
     * Nebucord_RESTExecutor destructor
     *
     * Frees the executor and the needed models after end.
     */
    public function __destruct()
    {
        $this->returnmodel = null;
        parent::__destruct();
    }

    /**
     * Executes a REST request immediately
     *
     * This function compbines the single request steps into one an returns
     * the data directly through the proxy class (NebucordREST::createRESTexecutor() if used).
     *
     * @see Nebucord_Model_REST
     * @see Nebucord_Status
     * @param string $requesttype The request type based on Nebucord_Status.
     * @param Nebucord_Model_REST $requestmodel The prepared model with data to be send to the rest gateway.
     * @return Nebucord_Model|array|null The data returned from the request (maybe as an array of data) or null if nothing is available.
     * @throws \Exception Throws an exception if the Nebucord_Model_REST is wrong.
     */
    public function execute(string $requesttype, Nebucord_Model_REST $requestmodel)
    {
        $this->createRESTAction($requesttype, $requestmodel);
        $this->executeREST();
        return $this->getRESTResponse();
    }

    /**
     * Executes a REST request immediately (array version)
     *
     * This function compbines the single request steps into one an returns
     * the data directly through the proxy class (NebucordREST::createRESTexecutor() if used).
     * This method is an alternative to the Nebucord_RESTExecutor::execute() method, where
     * payload can be passed as array instead of a model.
     *
     * @see Nebucord_Model_REST
     * @see Nebucord_Status
     * @param string $requesttype The request type based on Nebucord_Status.
     * @param array $param The payload for the request to be send to the gatewqy.
     * @return Nebucord_Model|array|null The data returned from the request (maybe as an array of data) or null if nothing is available.
     * @throws \Exception Throws an exception if the Nebucord_Model_REST is wrong.
     */
    public function executeFromArray(string $requesttype, array $params)
    {
        $this->createRESTActionFromArray($requesttype, $params);
        $this->executeREST();
        return $this->getRESTResponse();
    }

    /**
     * Creates the REST request
     *
     * Sets up the HTTP client for the rest request based on the given Nebucord_Model_REST and
     * Nebucord_Status.
     *
     * @see Nebucord_Model_REST
     * @see Nebucord_Status
     * @param string $requesttype The request type based on Nebucord_Status.
     * @param Nebucord_Model_REST $requestmodel The prepared model with data to be send to the rest gateway.
     * @throws \Exception Throws an exception if the Nebucord_Model_REST is wrong.
     */
    public function createRESTAction(string $requesttype, Nebucord_Model_REST $requestmodel)
    {
        $oRequestModel = Nebucord_Model_Factory::createREST($requesttype);
        $oRequestModel->populate($requestmodel->toArray());
        $this->_httpclient->setParams($requestmodel);
    }

    /**
     * Creates the REST request (array version)
     *
     * Sets up the HTTP client for the rest request based on the given array and
     * Nebucord_Status.
     * This method is an alternative to the Nebucord::createRestAction method within this class.
     *
     * @see Nebucord_Status
     * @param string $requesttype The request type based on Nebucord_Status.
     * @param array $param The payload for the request to be send to the gatewqy.
     * @throws \Exception Throws an exception if the Nebucord_Model_REST is wrong.
     */
    public function createRESTActionFromArray(string $requesttype, array $param)
    {
        $oRequestModel = Nebucord_Model_Factory::createREST($requesttype);
        $oRequestModel->populate($param);
        $this->_httpclient->setParams($oRequestModel);
    }

    /**
     * Executes the REST request
     *
     * After setting up the request, this method executes the request and stores the data within the
     * local private model if any data available.
     */
    public function executeREST()
    {
        $res = $this->_httpclient->execute();
        if(!is_null($res[1])) {
            if (count($res[1]) > 0 && self::checkReturnArrayType($res[1])) {
                $this->returnmodel = Nebucord_Model_Factory::create();
                $this->returnmodel->populate($res[1]);
                $this->returnmodel->http_status_code = $res[0];
                return;
            } else {
                for ($i = 0; $i < count($res[1]); $i++) {
                    $tmpmodel = Nebucord_Model_Factory::create();
                    $tmpmodel->populate($res[1][$i]);
                    $tmpmodel->http_status_code = $res[0];
                    $this->returnmodel[] = $tmpmodel;
                    unset($tmpmodel);
                }
                return;
            }
        }
        $this->returnmodel = Nebucord_Model_Factory::create();
        $this->returnmodel->http_status_code = $res[0];
    }

    /**
     * Get the request data
     *
     * After sending the request and the data is stored, this method returns the private model with
     * the data if any data available. If no data is available, the model should be null.
     *
     * @return Nebucord_Model|array|null The data returned from the request (maybe as an array of data) or null if nothing is available.
     */
    public function getRESTResponse()
    {
        return $this->returnmodel;
    }

    /**
     * Checks return type of data from gateway
     *
     * A simple check if data return from the request is a data model (represented as an associative array)
     * or as an array of data models (a role list for example) which is in turn a numeric array of associative data
     * arrays.
     *
     * @param array $array The data array to check for the array type.
     * @return bool return true or false based on the array type (true if it is an assoc array, otherwise false).
     */
    private static function checkReturnArrayType(array $array)
    {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }
}
