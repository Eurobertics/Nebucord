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

namespace Nebucord\Base;

/**
 * Class Nebucord_Status
 *
 * This class holds any status given by the Discord gateway. This includes OP codes and gateway events as well.
 * Beside this, here are some constants stored wich represents the Nebucord API version or the client identifications.
 *
 * For detailed status information visit the Discord developer site: https://discordapp.com/developers/docs/intro
 *
 * @package Nebucord\Base
 */
class Nebucord_Status {
    const OP_DISPATCH = 0;
    const OP_HEARTBEAT = 1;
    const OP_IDENTIFY = 2;
    const OP_STATUS_UPDATE = 3;
    const OP_VOICE_STATE_UPDATE = 4;
    const OP_VOICE_SERVER_PING = 5;
    const OP_RESUME = 6;
    const OP_RECONNECT = 7;
    const OP_REQUEST_GUILD_MEMBERS = 8;
    const OP_INVALID_SESSION = 9;
    const OP_HELLO = 10;
    const OP_HEARTBEAT_ACK = 11;

    const GWREQ_IDENTIFY = 2;
    const GWREQ_RESUME = 6;
    const GWREQ_HEARTBEAT = 1;
    const GWERQ_REQUEST_GUILD_MEMBERS = 8;
    const GWREQ_VOICE_STATE_UPDATE = 4;
    const GWREQ_UPDATE_PRESENCE = 3;

    const GWEVT_HELLO = 'HELLO';
    const GWEVT_READY = 'READY';
    const GWEVT_RESUMED = 'RESUMED';
    const GWEVT_RECONNECT = 'RECONNECT';
    const GWEVT_INVALID_SESSION = 'INVALID_SESSION';
    const GWEVT_CHANNEL_CREATE = 'CHANNEL_CREATE';
    const GWEVT_CHANNEL_UPDATE = 'CHANNEL_UPDATE';
    const GWEVT_CHANNEL_DELETE = 'CHANNEL_DELETE';
    const GWEVT_CHANNEL_PINS_UPDATE = 'CHANNEL_PINS_UPDATE';
    const GWEVT_THREAD_CREATE = 'THREAD_CREATE';
    const GWEVT_THREAD_UPDATE = 'THREAD_UPDATE';
    const GWEVT_THREAD_DELETE = 'THREAD_DELETE';
    const GWEVT_THREAD_LIST_SYNC = 'THREAD_LIST_SYNC';
    const GWEVT_THREAD_MEMBER_UPDATE = 'THREAD_MEMBER_UPDATE';
    const GWEVT_THREAD_MEMBERS_UPDATE = 'THREAD_MEMBERS_UPDATE';
    const GWEVT_GUILD_CREATE = 'GUILD_CREATE';
    const GWEVT_GUILD_UPDATE = 'GUILD_UPDATE';
    const GWEVT_GUILD_DELETE = 'GUILD_DELETE';
    const GWEVT_GUILD_BAN_ADD = 'GUILD_BAN_ADD';
    const GWEVT_GUILD_BAN_REMOVE = 'GUILD_BAN_REMOVE';
    const GWEVT_GUILD_EMOJIS_UPDATE = 'GUILD_EMOJIS_UPDATE';
    const GWEVT_GUILD_STICKERS_UPDATE = 'GUILD_STICKERS_UPDATE';
    const GWEVT_GUILD_INTEGRATION_UPDATE = 'GUILD_INTEGRATION_UPDATE';
    const GWEVT_GUILD_MEMBER_ADD = 'GUILD_MEMBER_ADD';
    const GWEVT_GUILD_MEMBER_REMOVE = 'GUILD_MEMBER_REMOVE';
    const GWEVT_GUILD_MEMBER_UPDATE = 'GUILD_MEMBER_UPDATE';
    const GWEVT_GUILD_MEMBERS_CHUNK = 'GUILD_MEMBERS_CHUNK';
    const GWEVT_GUILD_ROLE_CREATE = 'GUILD_ROLE_CREATE';
    const GWEVT_GUILD_ROLE_UPDATE = 'GUILD_ROLE_UPDATE';
    const GWEVT_GUILD_ROLE_DELETE = 'GUILD_ROLE_DELETE';
    const GWEVT_INTEGRATION_CREATE = 'INTEGRATION_CREATE';
    const GWEVT_INTEGRATION_UPDATE = 'INTEGRATION_UPDATE';
    const GWEVT_INTEGRATION_DELETE = 'INTEGRATION_DELETE';
    const GWEVT_INTERACTION_CREATE = 'INTERACTION_CREATE';
    const GWEVT_INVITE_CREATE = 'INVITE_CREATE';
    const GWEVT_INVITE_DELETE = 'INVITE_DELETE';
    const GWEVT_MESSAGE_CREATE = 'MESSAGE_CREATE';
    const GWEVT_MESSAGE_UPDATE = 'MESSAGE_UPDATE';
    const GWEVT_MESSAGE_DELETE = 'MESSAGE_DELETE';
    const GWEVT_MESSAGE_DELETE_BULK = 'MESSAGE_DELETE_BULK';
    const GWEVT_MESSAGE_REACTION_ADD = 'MESSAGE_REACTION_ADD';
    const GWEVT_MESSAGE_REACTION_REMOVE = 'MESSAGE_REACTION_REMOVE';
    const GWEVT_MESSAGE_REACTION_REMOVE_ALL = 'MESSAGE_REACTION_REMOVE_ALL';
    const GWEVT_MESSAGE_REACTION_REMOVE_EMOJY = 'MESSAGE_REACTION_REMOVE_EMOJY';
    const GWEVT_PRESENCE_UPDATE = 'PRESENCE_UPDATE';
    const GWEVT_STAGE_INSTANCE_CREATE = 'STAGE_INSTANCE_CREATE';
    const GWEVT_STAGE_INSTANCE_DELETE = 'STAGE_INSTANCE_DELETE';
    const GWEVT_STGE_INSTANCE_UPDATE = 'STAGE_INSTANCE_UPDATE';
    const GWEVT_TYPING_START = 'TYPEING_START';
    const GWEVT_USER_UPDATE = 'USER_UPDATE';
    const GWEVT_VOICE_STATE_UPDATE = 'VOICE_STATE_UPDATE';
    const GWEVT_VOICE_SERVER_UPDATE = 'VOICE_SERVER_UPDATE';
    const GWEVT_WEBHOOKS_UPDATE = 'WEBHOOKS_UPDATE';
    
    const REQ_CREATE_MESSAGE = 'REQ_CREATE_MESSAGE';
    const REQ_GUILD_CHANNELS = 'GET_GUILD_CHANNELS';
    const REQ_GUILD_LIST_MEMBERS = 'REQ_LIST_GUILD_MEMBERS';
    const REQ_GUILD_GET_ROLES = 'REQ_GUILD_GET_ROLES';
    const REQ_USER_CREATE_DM = 'REQ_CREATE_DM';
    const REQ_CHANNEL_ALL_MESSAGES = 'REQ_GET_ALL_CHANNEL_MESSAGES';
    const REQ_GUILD_GET_EMOJI = "REQ_GET_GUILD_EMOJI";
    const REQ_GUILD_GET_ALL_EMOJIS = "GET_GUILD_ALL_EMOJIS";
    const REQ_GET_USER = "REQ_GET_USER";
    const REQ_GET_GUILD = 'REQ_GET_GUILD';
    const REQ_GUILD_MODIFY_MEMBER = 'REQ_GUILD_MODIFY_MEMBER';
    const REQ_GUILD_MODIFY_CURRENT_NICK = 'REQ_GUILD_MODIFY_CURRENT_NICK';
    const REQ_GUILD_ADD_MEMBER_ROLE = 'REQ_GUILD_ADD_MEMBER_ROLE';
    const REQ_GUILD_REMOVE_MEMBER_ROLE = 'REQ_GUILD_REMOVE_MEMBER_ROLE';
    const REQ_GET_CHANNEL = 'REQ_GET_CHANNEL';
    const REQ_CREATE_GUILD_CHANNEL = 'REQ_CREATE_GUILD_CHANNEL';
    const REQ_MODIFY_CHANNEL_POS = 'REQ_MODIFY_CHANNEL_POS';
    const REQ_GET_GUILD_MEMBER = 'REQ_GET_GUILD_MEMBER';
    const REQ_CREATE_GUILD_MEMBER = 'REQ_CREATE_GUILD_MEMBER';
    const REQ_MODIFY_CURRENT_USER_NICK = 'REQ_MODIFY_CURRENT_USER_NICK';
    const REQ_GUILD_REMOVE_MEMBER = 'REQ_GUILD_REMOVE_MEMBER';

    const MODEL_MESSAGE = 'MODEL_MESSAGE';
    const MODEL_GUILD = 'MODEL_GUILD';
    const MODEL_CHANNEL = 'MODEL_CHANNEL';
    const MODEL_GUILDMEMBER = 'MODEL_GUILDMEMBER';
    const MODEL_USER = 'MODEL_USER';
    const MODEL_ROLE = 'MODEL_ROLE';
    const MODEL_EMOJI = 'MODEL_EMOJI';

    const CHANNEL_TYPE_GUILD_TXT = 0;
    const CHANNEL_TYPE_DM = 1;
    const CHANNEL_TYPE_GUILD_VOICE = 2;
    const CHANNEL_TYPE_GROUP_DM = 3;
    const CHANNEL_TYPE_GUILD_CATEGORY = 4;
    const CHANNEL_TYPE_GUILD_NEWS = 5;
    const CHANNEL_TYPE_GUILD_STORE = 6;

    const MESSAGE_DEFAULT = 0;
    const MESSAGE_RECIPIENT_ADD = 1;
    const MESSAGE_RECIPIENT_REMOVE = 2;
    const MESSAGE_CALL = 3;
    const MESSAGE_CHANNEL_NAME_CHANGE = 4;
    const MESSAGE_CHANNEL_ICON_CHANGE = 5;
    const MESSAGE_CHANNEL_PINNED_MESSAGE = 6;
    const MESSAGE_GUILD_MEMBER_JOIN = 7;
    const MESSAGE_USER_PREMIUM_GUILD_SUBSCRIPTION = 8;
    const MESSAGE_USER_PREMIUM_GUILD_SUBSCRIPTION_TIER_1 = 9;
    const MESSAGE_USER_PREMIUM_GUILD_SUBSCRIPTION_TIER_2 = 10;
    const MESSAGE_USER_PREMIUM_GUILD_SUBSCRIPTION_TIER_3 = 11;
    const MESSAGE_CHANNEL_FOLLOW_ADD = 12;
    const MESSAGE_GUILD_DISCOVER_DISQUALIFIED = 14;
    const MESSAGE_GUILD_DISCOVER_REQUALIFIED = 15;

    const MESSAGE_ACTTYPE_JOIN = 1;
    const MESSAGE_ACTTYPE_SPECTATE = 2;
    const MESSAGE_ACTTYPE_LISTEN = 3;
    const MESSAGE_JOIN_REQUEST = 4;

    const MESSAGE_FLAGMASK_CROSSPOSTED = 1 << 0;
    const MESSAGE_FLAGMASK_IS_CROSSPOSTET = 1 << 1;
    const MESSAGE_FLAGMASK_SUPPRESS_EMBEDS = 1 << 2;
    const MESSAGE_FLAGMASK_SOURCE_MESSAGE_DELETED = 1 << 3;
    const MESSAGE_FLAGMASK_URGEND = 1 << 4;

    const INTENT_GUILD = 1 << 0;
    const INTENT_GUILD_MEMBERS = 1 << 1;
    const INTENT_GUILD_BANS = 1 << 2;
    const INTENT_GUILD_EMOJIS = 1 << 3;
    const INTENT_GUILD_INTEGRATIONS = 1 << 4;
    const INTENT_GUILD_WEBHOOKS = 1 << 5;
    const INTENT_GUILD_INVITES = 1 << 6;
    const INTENT_GUILD_VOICE_STATES = 1 << 7;
    const INTENT_GUILD_PRESENCES = 1 << 8;
    const INTENT_GUILD_MESSAGES = 1 << 9;
    const INTENT_GUILD_MESSAGE_REACTIONS = 1 << 10;
    const INTENT_GUILD_MESSAGE_TYPING = 1 << 11;
    const INTENT_DIRECT_MESSAGES = 1 << 12;
    const INTENT_DIRECT_MESSAGE_REACTIONS = 1 << 13;
    const INTENT_DIRECT_MESSAGE_TYPING = 1 << 14;

    const INTENTS_DEFAULT_BITMASK = 32509;

    const NC_RUN = 1;
    const NC_EXIT = 0;
    const NC_RECONNECT = 2;

    const MAX_RECONNECT_TRIES = 3;

    const RATELIMIT_MAXREQUEST = 120;
    const RATELIMIT_TIMEFRAME = 60;

    const CLIENTBROWSER = 'NebucordWS API';
    const CLIENTHOST = 'nebucordws.nebulatien.org';
    const VERSION = '0.9.5.2';

    /**
     * Returns the OS.
     *
     * Returns the OS on wich Nebucord is running for gateway client identification.
     *
     * @return string The operating system wich this is running on.
     */
    static public function getOS() {
        return php_uname('s');
    }

    /**
     * Returns the client browser
     *
     * The Discord gateway expects a proper client identification, because the initial connect is a HTTP request.
     * This returns the client browser of the Discord API used to connect.
     *
     * @return string Client browser identification.
     */
    static public function getBrowser() {
        return self::CLIENTBROWSER;
    }

    /**
     * Returns the device of Nebucord
     *
     * Beside the client identification of browser and OS the device wich connects to the gateway is part
     * of a proper client identification as well. This returns the device (in this case PHP with version).
     *
     * @return string Device (software) on wich Nebucord is running.
     */
    static public function getDevice() {
        return "PHP".phpversion()." / ".php_uname('m')." Host";
    }
}
