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
 * On createing data models for a REST request this interface sets the minium requirements a model must implement.
 * Also this interface is used to set base class identification for some methods used for a inherited class.
 *
 * @package Nebucord\Interfaces
 */
Interface Nebucord_IModelREST {

    /**
     * Returns an API endpoint based on the model.
     *
     * Returns the API endpoint based on the model used.
     *
     * @return string The API endpoing used by a REST request.
     */
    public function getApiEndpoint();

    /**
     * Gets the request type for a model.
     *
     * Not all REST request using the same request type. This method returns the requested type by model.
     * Mostly inherited from Nebucord_Model.
     *
     * @return string The HTTP request type (such es POST, GET, PUT, etc.).
     */
    public function getRequestType();

    /**
     * Fills a model by an array.
     *
     * The given array will we iterated and stored in the modely key => property, $value => property value.
     * If exists.
     *
     * @param array $data The data to ba stored within the model.
     */
    public function populate(array $data);

    /**
     * Returns a array representation of a model.
     *
     * Every property wich exists in a model will be returned by "key=>value" pair in an array.
     * Methods are not exported. This is often needed to convert a model to array before preparing to send somewhere.
     *
     * @return array The array with the propertys=>values of the model.
     */
    public function toArray();
}