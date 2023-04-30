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

namespace Nebucord\Interfaces;

/**
 * Interface Nebucord_IModelREST
 *
 * On creating data models for a REST request this interface sets the minimum requirements a model must implement.
 * Also this interface is used to set base class identification for some methods used for a inherited class.
 *
 * @package Nebucord\Interfaces
 */
Interface Nebucord_IModelREST {

    /**
     * Sets the API endpoint for REST
     *
     * Sets the full API REST endpoint.
     *
     * @param string $endpoint The API REST endpoint.
     */
    public function setApiEndpoint(string $endpoint);

    /**
     * Gets the API endpoint for REST
     *
     * Gets the full current REST API endpoint. Mostly used for internal
     * https requests to the gateway.
     *
     * @see Nebucord_RESTHTTPClient
     * @return string The full REST endpoint.
     */
    public function getApiEndpoint();

    /**
     * Sets the HTTP REST request type
     *
     * The https request type (POST, PUT, etc.) is set here.
     *
     * @param string $requesttype The requested request type for the API endpoint.
     * @throws \Exception If wrong or unknown type is set, an exception is thrown.
     */
    public function setRequestType(string $requesttype);

    /**
     * Get the REST http request type
     *
     * Mostly for internal use for the REST gateway. Gets the http request type.
     *
     * @return string The http request type.
     */
    public function getRequestType();
}
