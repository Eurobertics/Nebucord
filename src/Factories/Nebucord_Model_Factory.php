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

namespace Nebucord\Factories;


use Nebucord\Base\Nebucord_Status;
use Nebucord\Interfaces\Nebucord_IModelREST;
use Nebucord\Models\Nebucord_Model;
use Nebucord\Models\Nebucord_Model_REST;

/**
 * Creates models
 *
 * Creates models based on OP code, gatewayevent or request event. Also used by the REST API.
 *
 * Class Nebucord_Model_Factory
 * @package Nebucord\Factories
 */
abstract class Nebucord_Model_Factory {

    /**
     * Craetes a model for incoming data.
     *
     * On receiving a gateway event, this static method creates the appropriate model for it,
     * based on the OP code and gateway event.
     *
     * If no OP code or gw-event is received, a standard empty model will be created.
     *
     * @param Nebucord_Status $opcode The OP code for the model to be crated.
     * @param Nebucord_Status $gwevent The gateway event for the model to be created.
     * @return Nebucord_Model The newly instantiated model.
     */
    public static function create($opcode = null, $gwevent = null)
    {
        if ($opcode == 0 && $gwevent != null) {
            return new Nebucord_Model($opcode, $gwevent);
        } else {
            return new Nebucord_Model($opcode);
        }
    }

    /**
     * Creates a request model.
     *
     * When sending back to the gateway, respectively the REST API of Discord, this method
     * creates the models which can be sent to the gateway by REST.
     *
     * @param string $request The ID of the REST request (example: Nebucord_RESTStatus::REST_CREATE_MESSAGE).
     * @return Nebucord_IModelREST The created and instantiated model for request.
     * @throws \Exception Throws an exception on unknown or wrong http request type.
     */
    public static function createREST(string $request)
    {
        return new Nebucord_Model_REST($request);
    }
}