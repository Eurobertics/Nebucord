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


use Nebucord\Base\StatusList;
use Nebucord\Interfaces\iModelREST;
use Nebucord\Models\Model;
use Nebucord\Models\ModelREST;

/**
 * Creates models
 *
 * Creates models based on OP code, gatewayevent or request event. Also used by the REST API.
 *
 * Class ModelFactory
 * @package Nebucord\Factories
 */
abstract class ModelFactory {

    /**
     * Craetes a model for incoming data.
     *
     * On receiving a gateway event, this static method creates the appropriate model for it,
     * based on the OP code and gateway event.
     *
     * If no OP code or gw-event is received, a standard empty model will be created.
     *
     * @param StatusList $opcode The OP code for the model to be crated.
     * @param StatusList $gwevent The gateway event for the model to be created.
     * @return Model The newly instantiated model.
     */
    public static function create($opcode = null, $gwevent = null)
    {
        if ($opcode == 0 && $gwevent != null) {
            return new Model($opcode, $gwevent);
        } else {
            return new Model($opcode);
        }
    }

    /**
     * Creates a request model.
     *
     * When sending back to the gateway, respectively the REST API of Discord, this method
     * creates the models which can be sent to the gateway by REST.
     *
     * @param string $request The ID of the REST request (example: RestStatusList::REST_CREATE_MESSAGE).
     * @return iModelREST The created and instantiated model for request.
     * @throws \Exception Throws an exception on unknown or wrong http request type.
     */
    public static function createREST(string $request)
    {
        return new ModelREST($request);
    }
}
