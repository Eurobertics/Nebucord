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

namespace Nebucord\REST\Action;

use Nebucord\Base\Nebucord_Status;
use Nebucord\Factories\Nebucord_Model_Factory;
use Nebucord\Models\Nebucord_Model_Channel;
use Nebucord\Models\Nebucord_Model_GuildMember;
use Nebucord\Models\Nebucord_Model_Role;
use Nebucord\REST\Base\Nebucord_RESTAction;

/**
 * Class Nebucord_RESTGuild
 *
 * Guild ressource action. This includes all actions regarding a Discord guild.
 *
 * @package Nebucord\REST\Action
 */
class Nebucord_RESTGuild extends Nebucord_RESTAction {

    /**
     * Nebucord_RESTGuild constructor.
     *
     * Sets up the basic needed ressources.
     *
     * @param \Nebucord\REST\Base\Nebucord_RESTHTTPClient $httpclient The REST http client for sending request.
     */
    public function __construct(&$httpclient) {
    	parent::__construct();
    	$this->_httpclient = $httpclient;
    }

    /**
     * Nebucord_RESTGuild destructor.
     *
     * After the request is send and an answer is received, cleans everything up.
     */
    public function __destruct() {
    	parent::__destruct();
    }

    /**
     * Requests all channels of guild.
     *
     * Returns an array of objects for all channels on the given guild id.
     *
     * @param integer $guildid The Guild ID to retrieve the channels from.
     * @return array|Nebucord_Model_Channel The array of models with the channel data.
     */
    public function getGuildChannels($guildid) {
        $oChannelRequest = Nebucord_Model_Factory::createREST(Nebucord_Status::REQ_GUILD_CHANNELS);
        $oChannelRequest->guildid = $guildid;
        $this->_httpclient->setParams($oChannelRequest);
        $res = $this->_httpclient->execute();
        $res_ar = array();
        for($i = 0; $i < count($res); $i++) {
            $oChannel = Nebucord_Model_Factory::createModel(Nebucord_Status::MODEL_CHANNEL);
            $oChannel->populate($res[$i]);
            $res_ar[] = $oChannel;
        }
        return $res_ar;
    }

    /**
     * Request all members of a guild.
     *
     * Returns an array of objects for alle guild members on the given guild id.
     * The object is a guild member mode object wich contains a user model object, since Discord differs
     * between members and users (a user are a member of the guild members object).
     *
     * @param integer $guildid The guild id to request the members from.
     * @param integer $limit The limit of members to retrieve (max 1000 per request).
     * @param integer $after Request members after this id, for possible paginations.
     * @return array|Nebucord_Model_GuildMember The array of models with the guild member data.
     */
    public function getGuildMembers($guildid, $limit = 1, $after = 0) {
        $oMemberRequest = Nebucord_Model_Factory::createREST(Nebucord_Status::REQ_GUILD_LIST_MEMBERS);
        $oMemberRequest->guildid = $guildid;
        $oMemberRequest->limit = $limit;
        $oMemberRequest->after = $after;
        $this->_httpclient->setParams($oMemberRequest);
        $res = $this->_httpclient->execute();
        $res_ar = array();
        for($i = 0; $i < count($res); $i++) {
            if(!is_null($res[$i]['user'])) {
                $oMember = Nebucord_Model_Factory::createModel(Nebucord_Status::MODEL_USER);
                $oMember->populate($res[$i]['user']);
                $res[$i]['user'] = $oMember;
            }
            $oGuildMember = Nebucord_Model_Factory::createModel(Nebucord_Status::MODEL_GUILDMEMBER);
            $oGuildMember->populate($res[$i]);
            $res_ar[] = $oGuildMember;
        }
        return $res_ar;
    }

    /**
     * Requests all guild roles.
     *
     * Returns all roles of a guild.
     *
     * @param integer $guildid The guild id from where the roles are from.
     * @return array|Nebucord_Model_Role An array of the roles from the guild.
     */
    public function getGuildRoles($guildid) {
        $oRoleGetRequest = Nebucord_Model_Factory::createREST(Nebucord_Status::REQ_GUILD_GET_ROLES);
        $oRoleGetRequest->guildid = $guildid;
        $this->_httpclient->setParams($oRoleGetRequest);
        $res = $this->_httpclient->execute();
        $res_ar = array();
        for($i = 0; $i < count($res); $i++) {
            $oRole = Nebucord_Model_Factory::createModel(Nebucord_Status::MODEL_ROLE);
            $oRole->populate($res[$i]);
            $res_ar[] = $oRole;
        }
        return $res_ar;
    }
}
