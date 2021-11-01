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

namespace Nebucord\REST\Base;

use Nebucord\Base\Nebucord_Status;
use Nebucord\Models\Nebucord_Model_REST;

/**
 * Class Nebucord_RESTBuildAPIEndpoints
 *
 * This class holds every REST request type and API endpoints for the factory class.
 *
 * For detailed REST information visit the Discord developer site: https://discordapp.com/developers/docs/intro
 *
 * @package Nebucord\REST\Base
 */
abstract class Nebucord_RESTBuildAPIEndpoints
{

    /**
     * Loads and builds the REST API endpoints
     *
     * This method is responsible for loading the CSV file with all
     * REST API data and to build the API endpoint.
     *
     * @see Nebucord_Status for $apiendpoint
     * @see Nebucord_Model_REST::toArray() for $param the be used
     * @param string $apiendpoint A Nebucord_Status REST request const which sets the API endpoint to be build
     * @param array $param The data used to build the API endpoint such as channel ids, guild ids limits and such things (mostly generated from Nebucord_Model_REST::toArray()
     * @return string The finished API endpoint ready to use for the REST HTTP client
     */
    public static function buildApiEndpoint(string $apiendpoint, array $param = array())
    {
        $oRestArrayLoader = new Nebucord_RESTAPIEndpointsLoader();
        $restarray = $oRestArrayLoader->getRestArray();
        $endpoint = $restarray[$apiendpoint][1];
        foreach($param as $pkey => $pvalue) {
            $endpoint = self::replaceParam($endpoint, $pkey, $pvalue, (($apiendpoint[0] == 'GET') ? true : false));
        }
        return substr($endpoint, 0, -1);
    }

    /**
     * Returns the request type for the API endpoint
     *
     * After loading the CSV file for the API endpoints, the request type is from the given
     * $apiendpoint var extracted and returned.
     *
     * @see Nebucord_Status for $apiendpoint
     * @see Nebucord_RESTBase_Abstract for possible request types
     * @param string $apiendpoint A Nebucord_Status REST request const which sets the API endpoint to be build
     * @return string The given request type ready to use for the REST HTTP client (such as GET, POST, PUT, etc.)
     */
    public static function setRequestType(string $apiendpoint) {
        $oRestArrayLoader = new Nebucord_RESTAPIEndpointsLoader();
        $restarray = $oRestArrayLoader->getRestArray();
        return $restarray[$apiendpoint][0];
    }

    /**
     * The function for replacing placeholder with data for endpoints
     *
     * Is an $apiendpoint loaded by the class::buildApiEndpoint() method, this helper method
     * replaces the placeholder in the $apiendpoint string with values used for the gateway. These
     * are such data like limits, channel- or guild ids and so on.
     *
     * @param string $apiendpoint The loaded REST API endpoint from the CSV file
     * @param $paramname mixed The parameter in the endpoint string to be searched for
     * @param $paramval mixed parameter to be inserted as data for the placeholder
     * @param bool $paramappendtourl If true, unreplaced params are appended to the API endpoint for GET requests
     * @return string The finished REST endpoint after replacing the param
     */
    private static function replaceParam(string $apiendpoint, $paramname, $paramval, bool $paramappendtourl = false)
    {
        if(is_array($paramval)) {
            return $apiendpoint;
        }
        $apiendpoint = str_replace('##' . strtoupper($paramname) . '##', $paramval, $apiendpoint, $count);
        if($paramappendtourl) {
            if ($count == 0) {
                $apiendpoint = $paramname . "=" . $paramval . "&";
            }
        }
        return $apiendpoint;
    }
}
