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
abstract class Nebucord_Status {
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
    const GWEVT_APPLICATION_COMMAND_PERMISSIONS_UPDATE = 'APPLICATION_COMMAND_PERMISSIONS_UPDATE';
    const GWEVT_AUTO_MODERATION_RULE_CREATE = 'AUTO_MODERATION_RULE_CREATE';
    const GWEVT_AUTO_MODERATION_RULE_UPDATE = 'AUTO_MODERATION_RULE_UPDATE';
    const GWEVT_AUTO_MODERATION_RULE_DELETE = 'AUTO_MODERATION_RULE_DELETE';
    const GWEVT_AUTO_MODERATION_RULE_EXECUTION = 'AUTO_MODERATION_RULE_EXECUTION';
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
    const GWEVT_GUILD_SCHEDULED_EVENT_CREATE = 'GUILD_SCHEDULED_EVENT_CREATE';
    const GWEVT_GUILD_SCHEDULED_EVENT_UPDATE = 'GUILD_SCHEDULED_EVENT_UPDATE';
    const GWEVT_GUILD_SCHEDULED_EVENT_DELETE = 'GUILD_SCHEDULED_EVENT_DELETE';
    const GWEVT_GUILD_SCHEDULED_EVENT_USER_ADD = 'GUILD_SCHEDULED_EVENT_USER_ADD';
    const GWEVT_GUILD_SCHEDULED_EVENT_USER_REMOVE = 'GUILD_SCHEDULED_EVENT_USER_REMOVE';
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
    const GWEVT_AUDIT_LOG = 'AUDIT_LOG';

    const MODEL_MESSAGE = 'MODEL_MESSAGE';
    const MODEL_GUILD = 'MODEL_GUILD';
    const MODEL_CHANNEL = 'MODEL_CHANNEL';
    const MODEL_GUILDMEMBER = 'MODEL_GUILDMEMBER';
    const MODEL_USER = 'MODEL_USER';
    const MODEL_ROLE = 'MODEL_ROLE';
    const MODEL_EMOJI = 'MODEL_EMOJI';
    const MODEL_AUDIT_LOG = 'MODEL_AUDIT_LOG';

    const CHANNEL_TYPE_GUILD_TXT = 0;
    const CHANNEL_TYPE_DM = 1;
    const CHANNEL_TYPE_GUILD_VOICE = 2;
    const CHANNEL_TYPE_GROUP_DM = 3;
    const CHANNEL_TYPE_GUILD_CATEGORY = 4;
    const CHANNEL_TYPE_GUILD_NEWS = 5;
    const CHANNEL_TYPE_GUILD_STORE = 6;
    const CHANNEL_TYPE_NEWS_THREAD = 10;
    const CHANNEL_TYPE_PUBLIC_THREAD = 11;
    const CHANNEL_TYPE_PRIVATE_THREAD = 12;
    const CHANNEL_TYPE_STAGE_VOICE = 13;

    const VIDEO_QUALITY_MODE_AUTO = 1;
    const VIDEO_QUALITY_MODE_FULL = 2;

    const MESSAGE_TYPE_DEFAULT = 0;
    const MESSAGE_TYPE_RECIPIENT_ADD = 1;
    const MESSAGE_TYPE_RECIPIENT_REMOVE = 2;
    const MESSAGE_TYPE_CALL = 3;
    const MESSAGE_TYPE_CHANNEL_NAME_CHANGE = 4;
    const MESSAGE_TYPE_CHANNEL_ICON_CHANGE = 5;
    const MESSAGE_TYPE_CHANNEL_PINNED_MESSAGE = 6;
    const MESSAGE_TYPE_GUILD_MEMBER_JOIN = 7;
    const MESSAGE_TYPE_USER_PREMIUM_GUILD_SUBSCRIPTION = 8;
    const MESSAGE_TYPE_USER_PREMIUM_GUILD_SUBSCRIPTION_TIER_1 = 9;
    const MESSAGE_TYPE_USER_PREMIUM_GUILD_SUBSCRIPTION_TIER_2 = 10;
    const MESSAGE_TYPE_USER_PREMIUM_GUILD_SUBSCRIPTION_TIER_3 = 11;
    const MESSAGE_TYPE_CHANNEL_FOLLOW_ADD = 12;
    const MESSAGE_TYPE_GUILD_DISCOVER_DISQUALIFIED = 14;
    const MESSAGE_TYPE_GUILD_DISCOVER_REQUALIFIED = 15;
    const MESSAGE_TYPE_GUILD_DISCOVERY_GRACE_PERIOD_INITIAL_WARNING = 16;
    const MESSAGE_TYPE_GUILD_DISCOVERY_GRACE_PERIOD_FINAL_WARNING = 17;
    const MESSAGE_TYPE_THREAD_CREATED = 18;
    const MESSAGE_TYPE_REPLY = 19;
    const MESSAGE_TYPE_CHAT_INPUT_COMMAND = 20;
    const MESSAGE_TYPE_THREAD_STARTER_MESSAGE = 21;
    const MESSAGE_TYPE_GUILD_INVITE_REMINDER = 22;
    const MESSAGE_TYPE_CONTEXT_MENU_COMMAND = 23;

    const MESSAGE_ACTTYPE_JOIN = 1;
    const MESSAGE_ACTTYPE_SPECTATE = 2;
    const MESSAGE_ACTTYPE_LISTEN = 3;
    const MESSAGE_JOIN_REQUEST = 4;

    const MESSAGE_FLAGMASK_CROSSPOSTED = 1 << 0;
    const MESSAGE_FLAGMASK_IS_CROSSPOSTET = 1 << 1;
    const MESSAGE_FLAGMASK_SUPPRESS_EMBEDS = 1 << 2;
    const MESSAGE_FLAGMASK_SOURCE_MESSAGE_DELETED = 1 << 3;
    const MESSAGE_FLAGMASK_URGEND = 1 << 4;
    const MESSAGE_FLAGMASK_HAS_THREAD = 1 << 5;
    const MESSAGE_FLAGMASK_EPHERMAL = 1 << 6;
    const MESSAGE_FLAGMASK_LOADING = 1 << 7;

    const GUILD_MESSAGE_NOTIFICATION_ALL_MESSAGES = 0;
    const GUILD_MESSAGE_NOTIFICATION_ONLY_MENTIONS = 1;

    const GUILD_EXPLICIT_CONTENT_FILTER_DISABLED = 0;
    const GUILD_EXPLICIT_CONTENT_FILTER_MEMBERS_WITHOUT_ROLES = 1;
    const GUILD_EXPLICIT_CONTENT_FILTER_ALL_MEMBERS = 2;

    const GUILD_MFA_NONE = 0;
    const GUILD_MFS_ELEVATED = 1;

    const GUILD_VERIFICATION_LEVEL_DEFAULT = 0;
    const GUILD_VERIFICATION_LEVEL_EXPLICIT = 1;
    const GUILD_VERIFICATION_LEVEL_SAFE = 2;
    const GUILD_VERIFICATION_AGE_RESTRICTED = 3;

    const GUILD_PREMIUM_NONE = 0;
    const GUILD_PREMIUM_TIER_1 = 1;
    const GUILD_PREMIUM_TIER_2 = 2;
    const GUILD_PREMIUM_TIER_3 = 3;

    const GUILD_SYSCHANNEL_FLAG_SUPPRESS_JOIN_NOTIFICATIONS = 1 << 0;
    const GUILD_SYSCHANNEL_FLAG_SUPPRESS_PREMIUM_SUBSCRIPTIONS = 1 << 1;
    const GUILD_SYSCHANNEL_FLAG_SUPPRESS_GUILD_REMINDER_NOTIFICATIONS = 1 << 2;

    const USER_FLAG_NONE = 0;
    const USER_FLAG_DISCORD_EMPLOYEE = 1 << 0;
    const USER_FLAG_PARTNER_SERVER_OWNER = 1 << 1;
    const USER_FLAG_HYPESQUAD_EVENTS = 1 << 2;
    const USER_FLAG_BUG_HUNTER_LEVEL_1 = 1 << 3;
    const USER_FLAG_HOUSE_BRAVERY = 1 << 6;
    const USER_FLAG_HOUSE_BRILLIANCE = 1 << 7;
    const USER_FLAG_HOUSE_BALANCE = 1 << 8;
    const USER_FLAG_EARLY_SUPPORTER = 1 << 9;
    const USER_FLAG_TEAM_USER = 1 << 10;
    const USER_FLAG_BUG_HUNTER_LEVEL_2 = 1 << 14;
    const USER_FLAG_VERIFIED_BOT = 1 << 16;
    const USER_FLAG_EARLY_VERIFIED_BOT_DEV = 1 << 17;
    const USER_FLAG_DISCORD_CERTIFIED_MODERATOR = 1 << 18;

    const USER_PREMIUM_TYPE_NONE = 0;
    const USER_PREMIUM_TYPE_NITRO_CLASSIC = 1;
    const USER_PREMIUM_TYPE_NITRO = 2;

    const AUDIT_LOG_EVT_ALL = 0;
    const AUDIT_LOG_EVT_GUILD_UPDATE = 1;
    const AUDIT_LOG_EVT_CHANNEL_CREATE = 10;
    const AUDIT_LOG_EVT_CHANNEL_UPDATE = 11;
    const AUDIT_LOG_EVT_CHANNEL_DELETE = 12;
    const AUDIT_LOG_EVT_CHANNEL_OVERWRITE_CREATE = 13;
    const AUDIT_LOG_EVT_CHANNEL_OVERWRITE_UPDATE = 14;
    const AUDIT_LOG_EVT_CHANNEL_OVERWRITE_DELETE = 15;
    const AUDIT_LOG_EVT_MEMBER_KICK = 20;
    const AUDIT_LOG_EVT_MEMBER_PRUNE = 21;
    const AUDIT_LOG_EVT_MEMBER_BAN_ADD = 22;
    const AUDIT_LOG_EVT_MEMBER_BAN_REMOVE = 23;
    const AUDIT_LOG_EVT_MEMBER_UPDATE = 24;
    const AUDIT_LOG_EVT_MEMBER_ROLE_UPDATE = 25;
    const AUDIT_LOG_EVT_MEMBER_MOVE = 26;
    const AUDIT_LOG_EVT_MEMBER_DISCONNECT = 27;
    const AUDIT_LOG_EVT_BOT_ADD = 28;
    const AUDIT_LOG_EVT_ROLE_CREATE = 30;
    const AUDIT_LOG_EVT_ROLE_UPDATE = 31;
    const AUDIT_LOG_EVT_ROLE_DELETE = 33;
    const AUDIT_LOG_EVT_INVITE_CREATE = 40;
    const AUDIT_LOG_EVT_INVITE_UPDATE = 41;
    const AUDIT_LOG_EVT_INVITE_DELETE = 42;
    const AUDIT_LOG_EVT_WEBHOOK_CREATE = 50;
    const AUDIT_LOG_EVT_WEBHOOK_UPDATE = 51;
    const AUDIT_LOG_EVT_WEBHOOK_DELETE = 52;
    const AUDIT_LOG_EVT_EMOJI_CREATE = 60;
    const AUDIT_LOG_EVT_EMOJI_UPDATE = 61;
    const AUDIT_LOG_EVT_EMOJI_DELETE = 62;
    const AUDIT_LOG_EVT_MESSAGE_DELETE = 72;
    const AUDIT_LOG_EVT_MESSAGE_BULK_DELETE = 73;
    const AUDIT_LOG_EVT_MESSAGE_PIN = 74;
    const AUDIT_LOG_EVT_MESSAGE_UNPIN = 75;
    const AUDIT_LOG_EVT_INTEGRATION_CREATE = 80;
    const AUDIT_LOG_EVT_INTEGRATION_UPDATE = 81;
    const AUDIT_LOG_EVT_INTEGRATION_DELETE = 82;
    const AUDIT_LOG_EVT_STAGE_INSTANCE_CREATE = 83;
    const AUDIT_LOG_EVT_STAGE_INSTANCE_UPDATE = 84;
    const AUDIT_LOG_EVT_STAGE_INSTANCE_DELETE = 85;
    const AUDIT_LOG_EVT_STICKER_CREATE = 90;
    const AUDIT_LOG_EVT_STICKER_UPDATE = 91;
    const AUDIT_LOG_EVT_STICKER_DELETE = 92;
    const AUDIT_LOG_EVT_THREAD_CREATE = 110;
    const AUDIT_LOG_EVT_THREAD_UPDATE = 111;
    const AUDIT_LOG_EVT_THREAD_DELETE = 112;

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
    const INTENT_MESSAGE_CONTENT = 1 << 15;
    const INTENT_GUILD_SCHEDULED_EVENTS = 1 << 16;
    const INTENT_AUTO_MODERATION_CONFIGURATION = 1 << 20;
    const INTENT_AUTO_MODERATION_EXECUTION = 1 << 21;

    const APPLICATION_TYPE_CHAT_INPUT = 1;
    const APPLICATION_TYPE_USER = 2;
    const APPLICATION_TYPE_MESSAGE = 3;

    const APPLICATION_OPT_TYPE_SUB_COMMAND = 1;
    const APPLICATION_OPT_TYPE_SUB_COMMAND_GROUP = 2;
    const APPLICATION_OPT_TYPE_STRING = 3;
    const APPLICATION_OPT_TYPE_INTEGER = 4;
    const APPLICATION_OPT_TYPE_BOOLEAN = 5;
    const APPLICATION_OPT_TYPE_USER = 6;
    const APPLICATION_OPT_TYPE_CHANNEL = 7;
    const APPLICATION_OPT_TYPE_ROLE = 8;
    const APPLICATION_OPT_TYPE_MENTIONABLE = 9;
    const APPLICATION_OPT_TYPE_NUMBER = 10;
    const APPLICATION_OPT_TYPE_ATTACHEMENT = 11;

    const MSG_COMPONENT_ACTION_ROW = 1;
    const MSG_COMPONENT_BUTTON = 2;
    const MSG_COMPONENT_SELECT_MENU = 3;
    const MSG_COMPONENT_TEXT_INPUT = 4;
    const MSG_COMPONENT_USER_SELECT = 5;
    const MSG_COMPONENT_ROLE_SELECT = 6;
    const MSG_COMPONENT_MENTIONABLE_SELECT = 7;
    const MSG_COMPONENT_CHANNEL_SELECT = 8;
    const MSG_INPUT_STYLE_SHORT = 1;
    const MSG_INPUT_STYLE_PARAGRAGH = 2;

    const INTERACTION_TYPE_PING = 1;
    const INTERACTION_TYPE_APPLICATION_COMMAND = 2;
    const INTERACTION_TYPE_MESSAGE_COMPONENT = 3;
    const INTERACTION_TYPE_APP_COMMAND_AUTO_COMPLETE = 4;
    const INTERACTION_TYPE_MODAL_SUBMIT = 5;

    const INTERACTION_CB_TYPE_PONG = 1;
    const INTERACTION_CB_TYPE_CHANNEL_MSG_WITH_SOURCE = 4;
    const INTERACTION_CB_TYPE_DEFERRED_CHANNEL_MSG_WITH_SOURCE = 5;
    const INTERACTION_CB_TYPE_DEFERRED_UPDATE_MSG = 6;
    const INTERACTION_CB_TYPE_UPDATE_MSG = 7;
    const INTERACTION_CB_TYPE_APP_COMMAND_AUTOCOMPLETE_RESULT = 8;
    const INTERACTION_CB_TYPE_MODAL = 9;

    const BUTTON_STYLES_PRIMARY = 1; // blurpel
    const BUTTON_STYLES_SECONDARY = 2; // grey
    const BUTTON_STYLES_SUCCESS = 3; // green
    const BUTTON_STYLES_DANGER = 4; // red
    const BUTTON_STYLES_LINK = 5; // grey, URL link

    const APPLICATION_FLAGS_GW_PRESENCE = 1 << 12;
    const APPLICATION_FLAGS_GW_PRESENCE_LIMIT = 1 << 13;
    const APPLICATION_FLAGS_GW_GUILD_MEMBERS = 1 << 14;
    const APPLICATION_FLAGS_GW_GUILD_MEMBERS_LIMITED = 1 << 15;
    const APPLICATION_FLAGS_VERIFICATION_PENDING_GUILD_LIMIT = 1 << 16;
    const APPLICATION_FLAGS_EMBEDDED = 1 << 17;
    const APPLICATION_FLAGS_GW_MESSAGE_CONTENT = 1 << 18;
    const APPLICATION_FLAGS_GW_MESSAGE_CONTENT_LIMITED = 1 << 19;
    
    const AUTO_MODERATION_TRIGGER_TYPE_KEYWORD = 1;
    const AUTO_MODERATION_TRIGGER_TYPE_SPAM = 2;
    const AUTO_MODERATION_TRIGGER_TYPE_KEYWORD_PRESET = 3;
    const AUTO_MODERATION_TRIGGER_TYPE_MENTION_SPAN = 4;
    
    const GUILD_SCHEDULED_EVENT_PRIVACY_LEVEL_GUILD_ONLY = 2;
    const GUILD_SCHEDULED_EVENT_ENTITY_TYPE_STAGE_INSTANCE = 1;
    const GUILD_SCHEDULED_EVENT_ENTITY_TYPE_VOICE = 2;
    const GUILD_SCHEDULED_EVENT_ENTITY_TYPE_EXTERNAL = 3;
    
    const GUILD_SCHEDULED_EVENT_STATUS_SCHEDULED = 1;
    const GUILD_SCHEDULED_EVENT_STATUS_ACTIVE = 2;
    const GUILD_SCHEDULED_EVENT_STATUS_COMPLETED = 3;
    const GUILD_SCHEDULED_EVENT_STATUS_CANCLED = 4;

    const INTENTS_DEFAULT_BITMASK = 31997;

    const DMONFAILURES_DEFAULT = true;

    const NC_RUN = 1;
    const NC_EXIT = 0;
    const NC_RECONNECT = 2;
    const NC_FULLRECONNECT = 3;

    const MAX_RECONNECT_TRIES = 3;

    const RATELIMIT_MAXREQUEST = 120;
    const RATELIMIT_TIMEFRAME = 60;

    const CLIENTBROWSER = 'NebucordWS API';
    const CLIENTHOST = 'nebucordws.nebulatien.org';
    const VERSION = '1.1';

    /**
     * Returns the OS.
     *
     * Returns the OS on wich Nebucord is running for gateway client identification.
     *
     * @return string The operating system wich this is running on.
     */
    public static function getOS() {
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
    public static function getBrowser() {
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
    public static function getDevice() {
        return "NebucordWS API / PHP".phpversion();
    }
}
