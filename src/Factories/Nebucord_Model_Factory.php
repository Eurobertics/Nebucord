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

/**
 * Creates models
 *
 * Creates models based on OP code, gatewayevent or request event. Also used by the REST API.
 *
 * Class Nebucord_Model_Factory
 * @package Nebucord\Factories
 */
class Nebucord_Model_Factory {

    /** @var array A table with available model events received by the gateway. */
    private static $_modelclasstable = array(
        Nebucord_Status::OP_DISPATCH => array(
            Nebucord_Status::GWEVT_READY => "Nebucord\Models\Nebucord_Model_GWReady",
            Nebucord_Status::GWEVT_GUILD_CREATE => "Nebucord\Models\Nebucord_Model_Guild",
            Nebucord_Status::GWEVT_MESSAGE_CREATE => "Nebucord\Models\Nebucord_Model_Message",
            Nebucord_Status::GWEVT_MESSAGE_UPDATE => "Nebucord\Models\Nebucord_Model_Message",
            Nebucord_Status::GWEVT_MESSAGE_DELETE => "Nebucord\Models\Nebucord_Model_GWMessageDelete",
            Nebucord_Status::GWEVT_GUILD_MEMBER_ADD => "Nebucord\Models\Nebucord_Model_GWGuildMemberAdd",
            Nebucord_Status::GWEVT_RESUMED => "Nebucord\Models\Nebucord_Model_GWResumed",
            Nebucord_Status::GWEVT_PRESENCE_UPDATE => "Nebucord\Models\Nebucord_Model_GWPresenceUpdate",
            Nebucord_Status::GWEVT_CHANNEL_CREATE => "Nebucord\Models\Nebucord_Model_Channel",
            Nebucord_Status::GWEVT_CHANNEL_UPDATE => "Nebucord\Models\Nebucord_Model_Channel",
            Nebucord_Status::GWEVT_CHANNEL_DELETE => "Nebucord\Models\Nebucord_Model_Channel"
        ),
        Nebucord_Status::OP_HEARTBEAT => "Nebucord\Models\Nebucord_Model_OPHeartbeat",
        Nebucord_Status::OP_IDENTIFY => "Nebucord\Models\Nebucord_Model_OPIdentify",
        Nebucord_Status::OP_STATUS_UPDATE => "Nebucord\Models\Nebucord_Model_OPStatusUpdate",
        Nebucord_Status::OP_RESUME => "Nebucord\Models\Nebucord_Model_OPResume",
        Nebucord_Status::OP_HELLO => "Nebucord\Models\Nebucord_Model_OPHello",
        Nebucord_Status::OP_HEARTBEAT_ACK => "Nebucord\Models\Nebucord_Model_OPHeartbeatACK"
    );

    /** @var array A table with available models wich used to send request. */
    private static $_modelclassreqtable = array(
	    Nebucord_Status::REQ_CREATE_MESSAGE => "Nebucord\Models\Nebucord_Model_RESTMessage",
        Nebucord_Status::REQ_GUILD_CHANNELS => "Nebucord\Models\Nebucord_Model_RESTGetGuildChannels",
        Nebucord_Status::REQ_GUILD_LIST_MEMBERS => "Nebucord\Models\Nebucord_Model_RESTListGuildMembers",
        Nebucord_Status::REQ_GUILD_GET_ROLES => "Nebucord\Models\Nebucord_Model_RESTGetGuildRoles",
        Nebucord_Status::REQ_USER_CREATE_DM => "Nebucord\Models\Nebucord_Model_RESTCreateDM",
        Nebucord_Status::REQ_CHANNEL_ALL_MESSAGES => "Nebucord\Models\Nebucord_Model_RESTGetChannelMessages",
        Nebucord_Status::REQ_GUILD_GET_EMOJI => "Nebucord\Models\Nebucord_Model_RESTGetGuildEmoji",
        Nebucord_Status::REQ_GUILD_GET_ALL_EMOJIS => "Nebucord\Models\Nebucord_Model_RESTListGuildEmojis",
        Nebucord_Status::REQ_GET_USER => "Nebucord\Models\Nebucord_Model_RESTGetUser",
        Nebucord_Status::REQ_GET_GUILD => "Nebucord\Models\Nebucord_Model_RESTGetGuild",
        Nebucord_Status::REQ_GUILD_MODIFY_MEMBER => "Nebucord\Models\Nebucord_Model_RESTModifyGuildMember",
        Nebucord_Status::REQ_GUILD_MODIFY_CURRENT_NICK => "Nebucord\Models\Nebucord_Model_RESTGuildModifyGuildMember",
        Nebucord_Status::REQ_GUILD_ADD_MEMBER_ROLE => "Nebucord\Models\Nebucord_Model_RESTGuildAddMemberRole",
        Nebucord_Status::REQ_GUILD_REMOVE_MEMBER_ROLE => "Nebucord\Models\Nebucord_Model_RESTGuildRemoveMemberRole",
	);

    /** @var array A table with available models data structues returned to a user callback for processing. */
    private static $_models = array(
        Nebucord_Status::MODEL_MESSAGE => "Nebucord\Models\Nebucord_Model_Message",
        Nebucord_Status::MODEL_CHANNEL => "Nebucord\Models\Nebucord_Model_Channel",
        Nebucord_Status::MODEL_GUILD => "Nebucord\Models\Nebucord_Model_Guild",
        Nebucord_Status::MODEL_GUILDMEMBER => "Nebucord\Models\Nebucord_Model_GuildMember",
        Nebucord_Status::MODEL_USER => "Nebucord\Models\Nebucord_Model_User",
        Nebucord_Status::MODEL_ROLE => "Nebucord\Models\Nebucord_Model_Role",
        Nebucord_Status::MODEL_EMOJI => "Nebucord\Models\Nebucord_Model_Emoji"
    );

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
    public static function create($opcode = null, $gwevent = null) {
        if ($opcode == 0 && $gwevent != null) {
            if(!isset(self::$_modelclasstable[$opcode][$gwevent])) {
                \Nebucord\Logging\Nebucord_Logger::warn("Creating default model, no appropriate model for event found.");
                return new Nebucord_Model($opcode, $gwevent);
            }
            return new self::$_modelclasstable[$opcode][$gwevent]($opcode, $gwevent);
        } else {
            if(!isset(self::$_modelclasstable[$opcode])) {
                \Nebucord\Logging\Nebucord_Logger::warn("Creating default model, no appropriate model for event found.");
                return new Nebucord_Model($opcode);
            }
           return new self::$_modelclasstable[$opcode]($opcode);
        }
    }

    /**
     * Craetes a request model.
     *
     * When sending back to the gateway, respectivley the REST API of Discord, this method
     * creates the models wich can be send to the gateway by REST.
     *
     * @param string $reqmodel The ID of the model to becreated.
     * @return Nebucord_IModelREST The created and instantiated model for request.
     */
    public static function createREST($reqmodel) {
    	return new self::$_modelclassreqtable[$reqmodel]();
    }

    /**
     * Creates a data model.
     *
     * If a request is send or received, here are the models created wich are holding data for such an event for
     * further processing.
     *
     * @param string $model The ID of the model to be created.
     * @return Nebucord_Model The created and instantiated model.
     */
    public static function createModel($model) {
        return new self::$_models[$model]();
    }
}