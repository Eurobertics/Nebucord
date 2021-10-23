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
    public static function buildApiEndpoint(string $apiendpoint, array $param = array())
    {
        $oRestArrayLoader = new Nebucord_RESTAPIEndpointsLoader();
        $restarray = $oRestArrayLoader->getRestArray();
        $endpoint = $restarray[$apiendpoint][1];
        foreach($param as $pkey => $pvalue) {
            $endpoint = self::replaceParam($endpoint,$pkey, $pvalue);
        }
        return preg_replace('/(\#\#)[a-zA-Z]*(\#\#)/', '', $endpoint);
    }

    public static function setRequestType(string $apiendpoint) {
        $oRestArrayLoader = new Nebucord_RESTAPIEndpointsLoader();
        $restarray = $oRestArrayLoader->getRestArray();
        return $restarray[$apiendpoint][0];
    }

    private static function replaceParam(string $apiendpoint, $paramname, $paramval)
    {
        return str_replace('##' . strtoupper($paramname) . '##', $paramval, $apiendpoint);
    }
}
