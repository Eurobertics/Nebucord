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

/**
 * Class Nebucord_RESTAction
 *
 * The REST part of the API, this is the base class for request actions.
 *
 * @package Nebucord\REST\Base
 */
class Nebucord_RESTAction {

    /** @var Nebucord_RESTHTTPClient $_httpclient The http client instance to send data. */
	protected $_httpclient;

	/** @var \Nebucord\Models\Nebucord_Model $_sendmodel The model to be send to the REST gateway.*/
	protected $_sendmodel;

    /**
     * Nebucord_RESTAction constructor.
     *
     * Sets itself up.
     */
	public function __construct() {
	}

    /**
     * Nebucord_RESTAction destructor.
     *
     * End itselfs and cleans up.
     */
	public function __destruct() {
	}
}
