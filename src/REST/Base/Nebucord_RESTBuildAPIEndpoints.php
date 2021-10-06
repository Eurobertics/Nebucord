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
        if(isset($param['guildid']) && !is_null($param['guildid'])) {
            $endpoint = self::replaceGuildId($endpoint, $param['guildid']);
        }
        if(isset($param['channelid']) && !is_null($param['channelid'])) {
            $endpoint = self::replaceChannelId($endpoint, $param['channelid']);
        }
        if(isset($param['userid']) && !is_null($param['userid'])) {
            $endpoint = self::replaceUserId($endpoint, $param['userid']);
        }
        if(isset($param['limit']) && !is_null($param['limit'])) {
            $endpoint = self::replaceLimit($endpoint, $param['limit']);
        }
        if(isset($param['around']) && !is_null($param['around'])) {
            $endpoint = self::replaceAround($endpoint, $param['around']);
        }
        if(isset($param['before']) && !is_null($param['before'])) {
            $endpoint = self::replaceBefore($endpoint, $param['before']);
        }
        if(isset($param['after']) && !is_null($param['after'])) {
            $endpoint = self::replaceAfter($endpoint, $param['after']);
        }
        if(isset($param['emojiid']) && !is_null($param['emojiid'])) {
            $endpoint = self::replaceAfter($endpoint, $param['emojiid']);
        }
        if(isset($param['roleid']) && !is_null($param['roleid'])) {
            $endpoint = self::replaceAfter($endpoint, $param['roleid']);
        }
        return $endpoint;
    }

    public static function setRequestType(string $apiendpoint) {
        $oRestArrayLoader = new Nebucord_RESTAPIEndpointsLoader();
        $restarray = $oRestArrayLoader->getRestArray();
        return $restarray[$apiendpoint][0];
    }

    private static function replaceGuildId(string $apiendpoint, int $guildid)
    {
        return str_replace('##GUILDID##', $guildid, $apiendpoint);
    }

    private static function replaceChannelId(string $apiendpoint, int $channelid)
    {
        return str_replace('##CHANNELID##', $channelid, $apiendpoint);
    }

    private static function replaceUserId(string $apiendpoint, int $userid)
    {
        return str_replace('##USERID##', $userid, $apiendpoint);
    }

    private static function replaceLimit(string $apiendpoint, int $limit)
    {
        return str_replace('##LIMIT##', $limit, $apiendpoint);
    }

    private static function replaceAround(string $apiendpoint, int $around)
    {
        return str_replace('##AROUNT##', "&around=".$around, $apiendpoint);
    }

    private static function replaceBefore(string $apiendpoint, int $before)
    {
        return str_replace('##BEFORE##', "&before=".$before, $apiendpoint);
    }

    private static function replaceAfter(string $apiendpoint, int $after)
    {
        return str_replace('##AFTER##', "&after=".$after, $apiendpoint);
    }

    private static function replaceEmojiId(string $apiendpoint, int $emojiid)
    {
        return str_replace('##EMOJIID##', $emojiid, $apiendpoint);
    }

    private static function replaceRoleId(string $apiendpoint, int $roleid)
    {
        return str_replace('##ROLEID##', $roleid, $apiendpoint);
    }
}
