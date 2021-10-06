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
use Nebucord\Models\Nebucord_Model_Guild;
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
        $oChannelRequest = Nebucord_Model_Factory::createREST(Nebucord_Status::REST_GUILD_CHANNELS);
        $oChannelRequest->guildid = $guildid;
        $this->_httpclient->setParams($oChannelRequest);
        $res = $this->_httpclient->execute();
        $res_ar = array();
        for($i = 0; $i < count($res); $i++) {
            $oChannel = Nebucord_Model_Factory::create();
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
        $oMemberRequest = Nebucord_Model_Factory::createREST(Nebucord_Status::REST_GUILD_LIST_MEMBERS);
        $oMemberRequest->guildid = $guildid;
        $oMemberRequest->limit = $limit;
        $oMemberRequest->after = $after;
        $this->_httpclient->setParams($oMemberRequest);
        $res = $this->_httpclient->execute();
        $res_ar = array();
        for($i = 0; $i < count($res); $i++) {
            if(!is_null($res[$i]['user'])) {
                $oMember = Nebucord_Model_Factory::create();
                $oMember->populate($res[$i]['user']);
                $res[$i]['user'] = $oMember;
            }
            $oGuildMember = Nebucord_Model_Factory::create();
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
        $oRoleGetRequest = Nebucord_Model_Factory::createREST(Nebucord_Status::REST_GUILD_GET_ROLES);
        $oRoleGetRequest->guildid = $guildid;
        $this->_httpclient->setParams($oRoleGetRequest);
        $res = $this->_httpclient->execute();
        $res_ar = array();
        for($i = 0; $i < count($res); $i++) {
            $oRole = Nebucord_Model_Factory::create();
            $oRole->populate($res[$i]);
            $res_ar[] = $oRole;
        }
        return $res_ar;
    }

    /**
     * Gets guild data and details
     *
     * Gets all data from a guild.
     *
     * @param integer $guildid The guild id to query.
     * @return Nebucord_Model_Guild The guild model with it's data.
     */
    public function getGuild($guildid) {
        $oGetGuildRequest = Nebucord_Model_Factory::createREST(Nebucord_Status::REST_GET_GUILD);
        $oGetGuildRequest->guildid = $guildid;
        $this->_httpclient->setParams($oGetGuildRequest);
        $res = $this->_httpclient->execute();
        /** @var Nebucord_Model_Guild $oGuildModel */
        $oGuildModel = Nebucord_Model_Factory::create();
        $oGuildModel->populate($res);
        return $oGuildModel;
    }

    /**
     *
     * Updates a guild member.
     *
     * Sets new data to a guild member.
     * Returns an empty response. So no return is given.
     *
     * @param integer $guildid The guild id to look for the member.
     * @param integer $userid The user (id) to modify.
     * @param null|string $newnick New nickname or null if not to be updated.
     * @param null|array $roles An array of new role ids or null if not to be updated.
     * @param null|bool $mute User set to (un)mute if in a voice channel or null if not to be updated.
     * @param null|bool $deaf User set to (un)deafened if in a voice channel or null if not to be updated.
     * @param null $channelid Channel id to move user in or null if not to be moved.
     */
    public function modifyGuildMember($guildid, $userid, $newnick = null, $roles = null, $mute = null, $deaf = null, $channelid = null) {
        $oModifyGuildUser = Nebucord_Model_Factory::createREST(Nebucord_Status::REST_GUILD_MODIFY_MEMBER);
        $oModifyGuildUser->guildid = $guildid;
        $oModifyGuildUser->userid = $userid;
        $oModifyGuildUser->nick = $newnick;
        $oModifyGuildUser->roles = $roles;
        $oModifyGuildUser->mute = $mute;
        $oModifyGuildUser->deaf = $deaf;
        $oModifyGuildUser->channel_id = $channelid;
        $this->_httpclient->setParams($oModifyGuildUser);
        $this->_httpclient->execute();
    }

    /**
     *
     * Updates the current user nick (@me).
     *
     * Sets a new name for the current guild user nick name.
     *
     * @param integer $guildid The guild id to look for the member.
     * @param null|string $newnick New nickname for @me user.
     */
    public function modifyCurrentNickname($guildid, $newnick) {
        $oModifyGuildUserNick = Nebucord_Model_Factory::createREST(Nebucord_Status::REST_GUILD_MODIFY_CURRENT_NICK);
        $oModifyGuildUserNick->guildid = $guildid;
        $oModifyGuildUserNick->nick = $newnick;
        $this->_httpclient->setParams($oModifyGuildUserNick);
        $this->_httpclient->execute();
    }

    /**
     *
     * Adds a role to user.
     *
     * Adds a role to the user within a guild.
     *
     * @param integer $guildid The guild id to look for the member.
     * @param integer $userid The user (id) to add the role.
     * @param integer $roleid The role id to be added.
     */
    public function addMemberRole($guildid, $userid, $roleid) {
        $oAddGuildUserRole = Nebucord_Model_Factory::createREST(Nebucord_Status::REST_GUILD_ADD_MEMBER_ROLE);
        $oAddGuildUserRole->guildid = $guildid;
        $oAddGuildUserRole->userid = $userid;
        $oAddGuildUserRole->roleid = $roleid;
        $this->_httpclient->setParams($oAddGuildUserRole);
        $this->_httpclient->execute();
    }

    /**
     *
     * Removes a role to user.
     *
     * Removes a role to the user within a guild.
     *
     * @param integer $guildid The guild id to look for the member.
     * @param integer $userid The user (id) to remnove the role.
     * @param integer $roleid The role id to be removed.
     */
    public function removeMemberRole($guildid, $userid, $roleid) {
        $oRemoveGuildUserRole = Nebucord_Model_Factory::createREST(Nebucord_Status::REST_GUILD_REMOVE_MEMBER_ROLE);
        $oRemoveGuildUserRole->guildid = $guildid;
        $oRemoveGuildUserRole->userid = $userid;
        $oRemoveGuildUserRole->roleid = $roleid;
        $this->_httpclient->setParams($oRemoveGuildUserRole);
        $this->_httpclient->execute();
    }
}
