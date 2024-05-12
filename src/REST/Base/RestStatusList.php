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
 * Class RestStatusList
 *
 * ATTENTION: This class is autogenerated!
 * This class stores the possible REST actions as consts used by the REST API to send requests.
 *
 * For detailed status information visit the Discord developer site: https://discordapp.com/developers/docs/intro
 *
 * @package Nebucord\REST\Base
 */
abstract class RestStatusList {
	const REST_GET_CURRENT_APPLICATION = 'REST_GET_CURRENT_APPLICATION';
	const REST_EDIT_CURRENT_APPLICATION = 'REST_EDIT_CURRENT_APPLICATION';
	const REST_GET_APPLICATION_ROLE_CONNECTION_METADATA_RECORDS = 'REST_GET_APPLICATION_ROLE_CONNECTION_METADATA_RECORDS';
	const REST_UPDATE_APPLICATION_ROLE_CONNECTION_METADATA_RECORDS = 'REST_UPDATE_APPLICATION_ROLE_CONNECTION_METADATA_RECORDS';
	const REST_GET_GUILD_AUDIT_LOG = 'REST_GET_GUILD_AUDIT_LOG';
	const REST_LIST_AUTO_MODERATION_RULES_FOR_GUILD = 'REST_LIST_AUTO_MODERATION_RULES_FOR_GUILD';
	const REST_GET_AUTO_MODERATION_RULE = 'REST_GET_AUTO_MODERATION_RULE';
	const REST_CREATE_AUTO_MODERATION_RULE = 'REST_CREATE_AUTO_MODERATION_RULE';
	const REST_MODIFY_AUTO_MODERATION_RULE = 'REST_MODIFY_AUTO_MODERATION_RULE';
	const REST_DELETE_AUTO_MODERATION_RULE = 'REST_DELETE_AUTO_MODERATION_RULE';
	const REST_GET_CHANNEL = 'REST_GET_CHANNEL';
	const REST_MODIFY_CHANNEL = 'REST_MODIFY_CHANNEL';
	const REST_DELETE = 'REST_DELETE';
	const REST_GET_CHANNEL_MESSAGES = 'REST_GET_CHANNEL_MESSAGES';
	const REST_GET_CHANNEL_MESSAGE = 'REST_GET_CHANNEL_MESSAGE';
	const REST_CREATE_MESSAGE = 'REST_CREATE_MESSAGE';
	const REST_CROSSPOST_MESSAGE = 'REST_CROSSPOST_MESSAGE';
	const REST_CREATE_REACTION = 'REST_CREATE_REACTION';
	const REST_DELETE_OWN_REACTION = 'REST_DELETE_OWN_REACTION';
	const REST_DELETE_USER_REACTION = 'REST_DELETE_USER_REACTION';
	const REST_GET_REACTIONS = 'REST_GET_REACTIONS';
	const REST_DELETE_ALL_REACTIONS = 'REST_DELETE_ALL_REACTIONS';
	const REST_DELETE_ALL_REACTIONS_FOR_EMOJI = 'REST_DELETE_ALL_REACTIONS_FOR_EMOJI';
	const REST_EDIT_MESSAGE = 'REST_EDIT_MESSAGE';
	const REST_DELETE_MESSAGE = 'REST_DELETE_MESSAGE';
	const REST_BULK_DELETE_MESSAGES = 'REST_BULK_DELETE_MESSAGES';
	const REST_EDIT_CHANNEL_PERMISSIONS = 'REST_EDIT_CHANNEL_PERMISSIONS';
	const REST_GET_CHANNEL_INVITES = 'REST_GET_CHANNEL_INVITES';
	const REST_CREATE_CHANNEL_INVITE = 'REST_CREATE_CHANNEL_INVITE';
	const REST_DELETE_CHANNEL_PERMISSION = 'REST_DELETE_CHANNEL_PERMISSION';
	const REST_FOLLOW_ANNOUNCEMENT_CHANNEL = 'REST_FOLLOW_ANNOUNCEMENT_CHANNEL';
	const REST_TRIGGER_TYPING_INDICATOR = 'REST_TRIGGER_TYPING_INDICATOR';
	const REST_GET_PINNED_MESSAGES = 'REST_GET_PINNED_MESSAGES';
	const REST_PIN_MESSAGE = 'REST_PIN_MESSAGE';
	const REST_UNPIN_MESSAGE = 'REST_UNPIN_MESSAGE';
	const REST_GROUP_DM_ADD_RECIPIENT = 'REST_GROUP_DM_ADD_RECIPIENT';
	const REST_GROUP_DM_REMOVE_RECIPIENT = 'REST_GROUP_DM_REMOVE_RECIPIENT';
	const REST_START_THREAD_FROM_MESSAGE = 'REST_START_THREAD_FROM_MESSAGE';
	const REST_START_THREAD_WITHOUT_MESSAGE = 'REST_START_THREAD_WITHOUT_MESSAGE';
	const REST_START_THREAD_IN_FORUM_OR_MEDIA_CHANNEL = 'REST_START_THREAD_IN_FORUM_OR_MEDIA_CHANNEL';
	const REST_JOIN_THREAD = 'REST_JOIN_THREAD';
	const REST_ADD_THREAD_MEMBER = 'REST_ADD_THREAD_MEMBER';
	const REST_LEAVE_THREAD = 'REST_LEAVE_THREAD';
	const REST_REMOVE_THREAD_MEMBER = 'REST_REMOVE_THREAD_MEMBER';
	const REST_GET_THREAD_MEMBER = 'REST_GET_THREAD_MEMBER';
	const REST_LIST_THREAD_MEMBERS = 'REST_LIST_THREAD_MEMBERS';
	const REST_LIST_PUBLIC_ARCHIVED_THREADS = 'REST_LIST_PUBLIC_ARCHIVED_THREADS';
	const REST_LIST_PRIVATE_ARCHIVED_THREADS = 'REST_LIST_PRIVATE_ARCHIVED_THREADS';
	const REST_LIST_JOINED_PRIVATE_ARCHIVED_THREADS = 'REST_LIST_JOINED_PRIVATE_ARCHIVED_THREADS';
	const REST_LIST_GUILD_EMOJIS = 'REST_LIST_GUILD_EMOJIS';
	const REST_GET_GUILD_EMOJI = 'REST_GET_GUILD_EMOJI';
	const REST_CREATE_GUILD_EMOJI = 'REST_CREATE_GUILD_EMOJI';
	const REST_MODIFY_GUILD_EMOJI = 'REST_MODIFY_GUILD_EMOJI';
	const REST_DELETE_GUILD_EMOJI = 'REST_DELETE_GUILD_EMOJI';
	const REST_CREATE_GUILD = 'REST_CREATE_GUILD';
	const REST_GET_GUILD = 'REST_GET_GUILD';
	const REST_GET_GUILD_PREVIEW = 'REST_GET_GUILD_PREVIEW';
	const REST_MODIFY_GUILD = 'REST_MODIFY_GUILD';
	const REST_DELETE_GUILD = 'REST_DELETE_GUILD';
	const REST_GET_GUILD_CHANNELS = 'REST_GET_GUILD_CHANNELS';
	const REST_CREATE_GUILD_CHANNEL = 'REST_CREATE_GUILD_CHANNEL';
	const REST_MODIFY_GUILD_CHANNEL_POSITIONS = 'REST_MODIFY_GUILD_CHANNEL_POSITIONS';
	const REST_LIST_ACTIVE_GUILD_THREADS = 'REST_LIST_ACTIVE_GUILD_THREADS';
	const REST_GET_GUILD_MEMBER = 'REST_GET_GUILD_MEMBER';
	const REST_LIST_GUILD_MEMBERS = 'REST_LIST_GUILD_MEMBERS';
	const REST_SEARCH_GUILD_MEMBERS = 'REST_SEARCH_GUILD_MEMBERS';
	const REST_ADD_GUILD_MEMBER = 'REST_ADD_GUILD_MEMBER';
	const REST_MODIFY_GUILD_MEMBER = 'REST_MODIFY_GUILD_MEMBER';
	const REST_MODIFY_CURRENT_MEMBER = 'REST_MODIFY_CURRENT_MEMBER';
	const REST_MODIFY_CURRENT_USER_NICK = 'REST_MODIFY_CURRENT_USER_NICK';
	const REST_ADD_GUILD_MEMBER_ROLE = 'REST_ADD_GUILD_MEMBER_ROLE';
	const REST_REMOVE_GUILD_MEMBER_ROLE = 'REST_REMOVE_GUILD_MEMBER_ROLE';
	const REST_REMOVE_GUILD_MEMBER = 'REST_REMOVE_GUILD_MEMBER';
	const REST_GET_GUILD_BANS = 'REST_GET_GUILD_BANS';
	const REST_GET_GUILD_BAN = 'REST_GET_GUILD_BAN';
	const REST_CREATE_GUILD_BAN = 'REST_CREATE_GUILD_BAN';
	const REST_REMOVE_GUILD_BAN = 'REST_REMOVE_GUILD_BAN';
	const REST_BULK_GUILD_BAN = 'REST_BULK_GUILD_BAN';
	const REST_GET_GUILD_ROLES = 'REST_GET_GUILD_ROLES';
	const REST_CREATE_GUILD_ROLE = 'REST_CREATE_GUILD_ROLE';
	const REST_MODIFY_GUILD_ROLE_POSITIONS = 'REST_MODIFY_GUILD_ROLE_POSITIONS';
	const REST_MODIFY_GUILD_ROLE = 'REST_MODIFY_GUILD_ROLE';
	const REST_MODIFY_GUILD_MFA_LEVEL = 'REST_MODIFY_GUILD_MFA_LEVEL';
	const REST_DELETE_GUILD_ROLE = 'REST_DELETE_GUILD_ROLE';
	const REST_GET_GUILD_PRUNE_COUNT = 'REST_GET_GUILD_PRUNE_COUNT';
	const REST_BEGIN_GUILD_PRUNE = 'REST_BEGIN_GUILD_PRUNE';
	const REST_GET_GUILD_VOICE_REGIONS = 'REST_GET_GUILD_VOICE_REGIONS';
	const REST_GET_GUILD_INVITES = 'REST_GET_GUILD_INVITES';
	const REST_GET_GUILD_INTEGRATIONS = 'REST_GET_GUILD_INTEGRATIONS';
	const REST_DELETE_GUILD_INTEGRATION = 'REST_DELETE_GUILD_INTEGRATION';
	const REST_GET_GUILD_WIDGET_SETTINGS = 'REST_GET_GUILD_WIDGET_SETTINGS';
	const REST_MODIFY_GUILD_WIDGET = 'REST_MODIFY_GUILD_WIDGET';
	const REST_GET_GUILD_WIDGET = 'REST_GET_GUILD_WIDGET';
	const REST_GET_GUILD_VANITY_URL = 'REST_GET_GUILD_VANITY_URL';
	const REST_GET_GUILD_WIDGET_IMAGE = 'REST_GET_GUILD_WIDGET_IMAGE';
	const REST_GET_GUILD_WELCOME_SCREEN = 'REST_GET_GUILD_WELCOME_SCREEN';
	const REST_MODIFY_GUILD_WELCOME_SCREEN = 'REST_MODIFY_GUILD_WELCOME_SCREEN';
	const REST_GET_GUILD_ONBOARDING = 'REST_GET_GUILD_ONBOARDING';
	const REST_MODIFY_GUILD_ONBOARDING = 'REST_MODIFY_GUILD_ONBOARDING';
	const REST_MODIFY_CURRENT_USER_VOICE_STATE = 'REST_MODIFY_CURRENT_USER_VOICE_STATE';
	const REST_MODIFY_USER_VOICE_STATE = 'REST_MODIFY_USER_VOICE_STATE';
	const REST_LIST_SCHEDULED_EVENTS_FOR_GUILD = 'REST_LIST_SCHEDULED_EVENTS_FOR_GUILD';
	const REST_CREATE_GUILD_SCHEDULED_EVENT = 'REST_CREATE_GUILD_SCHEDULED_EVENT';
	const REST_GET_GUILD_SCHEDULED_EVENT = 'REST_GET_GUILD_SCHEDULED_EVENT';
	const REST_MODIFY_GUILD_SCHEDULED_EVENT = 'REST_MODIFY_GUILD_SCHEDULED_EVENT';
	const REST_DELETE_GUILD_SCHEDULED_EVENT = 'REST_DELETE_GUILD_SCHEDULED_EVENT';
	const REST_GET_GUILD_SCHEDULED_EVENT_USERS = 'REST_GET_GUILD_SCHEDULED_EVENT_USERS';
	const REST_GET_GUILD_TEMPLATE = 'REST_GET_GUILD_TEMPLATE';
	const REST_CREATE_GUILD_FROM_GUILD_TEMPLATE = 'REST_CREATE_GUILD_FROM_GUILD_TEMPLATE';
	const REST_GET_GUILD_TEMPLATES = 'REST_GET_GUILD_TEMPLATES';
	const REST_CREATE_GUILD_TEMPLATE = 'REST_CREATE_GUILD_TEMPLATE';
	const REST_SYNC_GUILD_TEMPLATE = 'REST_SYNC_GUILD_TEMPLATE';
	const REST_MODIFY_GUILD_TEMPLATE = 'REST_MODIFY_GUILD_TEMPLATE';
	const REST_DELETE_GUILD_TEMPLATE = 'REST_DELETE_GUILD_TEMPLATE';
	const REST_GET_INVITE = 'REST_GET_INVITE';
	const REST_DELETE_INVITE = 'REST_DELETE_INVITE';
	const REST_GET_ANSWER_VOTERS = 'REST_GET_ANSWER_VOTERS';
	const REST_END_POLL = 'REST_END_POLL';
	const REST_CREATE_STAGE_INSTANCE = 'REST_CREATE_STAGE_INSTANCE';
	const REST_GET_STAGE_INSTANCE = 'REST_GET_STAGE_INSTANCE';
	const REST_MODIFY_STAGE_INSTANCE = 'REST_MODIFY_STAGE_INSTANCE';
	const REST_DELETE_STAGE_INSTANCE = 'REST_DELETE_STAGE_INSTANCE';
	const REST_GET_STICKER = 'REST_GET_STICKER';
	const REST_LIST_STICKER_PACKS = 'REST_LIST_STICKER_PACKS';
	const REST_LIST_GUILD_STICKERS = 'REST_LIST_GUILD_STICKERS';
	const REST_GET_GUILD_STICKER = 'REST_GET_GUILD_STICKER';
	const REST_CREATE_GUILD_STICKER = 'REST_CREATE_GUILD_STICKER';
	const REST_MODIFY_GUILD_STICKER = 'REST_MODIFY_GUILD_STICKER';
	const REST_DELETE_GUILD_STICKER = 'REST_DELETE_GUILD_STICKER';
	const REST_GET_CURRENT_USER = 'REST_GET_CURRENT_USER';
	const REST_GET_USER = 'REST_GET_USER';
	const REST_MODIFY_CURRENT_USER = 'REST_MODIFY_CURRENT_USER';
	const REST_GET_CURRENT_USER_GUILDS = 'REST_GET_CURRENT_USER_GUILDS';
	const REST_GET_CURRENT_USER_GUILD_MEMBER = 'REST_GET_CURRENT_USER_GUILD_MEMBER';
	const REST_LEAVE_GUILD = 'REST_LEAVE_GUILD';
	const REST_CREATE_DM = 'REST_CREATE_DM';
	const REST_CREATE_GROUP_DM = 'REST_CREATE_GROUP_DM';
	const REST_GET_CURRENT_USER_CONNECTIONS = 'REST_GET_CURRENT_USER_CONNECTIONS';
	const REST_GET_CURRENT_USER_APPLICATION_ROLE_CONNECTION = 'REST_GET_CURRENT_USER_APPLICATION_ROLE_CONNECTION';
	const REST_UPDATE_CURRENT_USER_APPLICATION_ROLE_CONNECTION = 'REST_UPDATE_CURRENT_USER_APPLICATION_ROLE_CONNECTION';
	const REST_LIST_VOICE_REGIONS = 'REST_LIST_VOICE_REGIONS';
	const REST_CREATE_WEBHOOK = 'REST_CREATE_WEBHOOK';
	const REST_GET_CHANNEL_WEBHOOKS = 'REST_GET_CHANNEL_WEBHOOKS';
	const REST_GET_GUILD_WEBHOOKS = 'REST_GET_GUILD_WEBHOOKS';
	const REST_GET_WEBHOOK = 'REST_GET_WEBHOOK';
	const REST_GET_WEBHOOK_WITH_TOKEN = 'REST_GET_WEBHOOK_WITH_TOKEN';
	const REST_MODIFY_WEBHOOK = 'REST_MODIFY_WEBHOOK';
	const REST_MODIFY_WEBHOOK_WITH_TOKEN = 'REST_MODIFY_WEBHOOK_WITH_TOKEN';
	const REST_DELETE_WEBHOOK = 'REST_DELETE_WEBHOOK';
	const REST_DELETE_WEBHOOK_WITH_TOKEN = 'REST_DELETE_WEBHOOK_WITH_TOKEN';
	const REST_EXECUTE_WEBHOOK = 'REST_EXECUTE_WEBHOOK';
	const REST_EXECUTE_SLACK_COMPATIBLE_WEBHOOK = 'REST_EXECUTE_SLACK_COMPATIBLE_WEBHOOK';
	const REST_EXECUTE_GITHUB_COMPATIBLE_WEBHOOK = 'REST_EXECUTE_GITHUB_COMPATIBLE_WEBHOOK';
	const REST_GET_WEBHOOK_MESSAGE = 'REST_GET_WEBHOOK_MESSAGE';
	const REST_EDIT_WEBHOOK_MESSAGE = 'REST_EDIT_WEBHOOK_MESSAGE';
	const REST_DELETE_WEBHOOK_MESSAGE = 'REST_DELETE_WEBHOOK_MESSAGE';
	const REST_GET_GLOBAL_APPLICATION_COMMANDS = 'REST_GET_GLOBAL_APPLICATION_COMMANDS';
	const REST_CREATE_GLOBAL_APPLICATION_COMMAND = 'REST_CREATE_GLOBAL_APPLICATION_COMMAND';
	const REST_GET_GLOBAL_APPLICATION_COMMAND = 'REST_GET_GLOBAL_APPLICATION_COMMAND';
	const REST_EDIT_GLOBAL_APPLICATION_COMMAND = 'REST_EDIT_GLOBAL_APPLICATION_COMMAND';
	const REST_DELETE_GLOBAL_APPLICATION_COMMAND = 'REST_DELETE_GLOBAL_APPLICATION_COMMAND';
	const REST_BULK_OVERWRITE_GLOBAL_APPLICATION_COMMANDS = 'REST_BULK_OVERWRITE_GLOBAL_APPLICATION_COMMANDS';
	const REST_GET_GUILD_APPLICATION_COMMANDS = 'REST_GET_GUILD_APPLICATION_COMMANDS';
	const REST_CREATE_GUILD_APPLICATION_COMMAND = 'REST_CREATE_GUILD_APPLICATION_COMMAND';
	const REST_GET_GUILD_APPLICATION_COMMAND = 'REST_GET_GUILD_APPLICATION_COMMAND';
	const REST_EDIT_GUILD_APPLICATION_COMMAND = 'REST_EDIT_GUILD_APPLICATION_COMMAND';
	const REST_DELETE_GUILD_APPLICATION_COMMAND = 'REST_DELETE_GUILD_APPLICATION_COMMAND';
	const REST_BULK_OVERWRITE_GUILD_APPLICATION_COMMANDS = 'REST_BULK_OVERWRITE_GUILD_APPLICATION_COMMANDS';
	const REST_GET_GUILD_APPLICATION_COMMAND_PERMISSIONS = 'REST_GET_GUILD_APPLICATION_COMMAND_PERMISSIONS';
	const REST_GET_APPLICATION_COMMAND_PERMISSIONS = 'REST_GET_APPLICATION_COMMAND_PERMISSIONS';
	const REST_EDIT_APPLICATION_COMMAND_PERMISSIONS = 'REST_EDIT_APPLICATION_COMMAND_PERMISSIONS';
	const REST_BATCH_EDIT_APPLICATION_COMMAND_PERMISSIONS = 'REST_BATCH_EDIT_APPLICATION_COMMAND_PERMISSIONS';
	const REST_CREATE_INTERACTION_RESPONSE = 'REST_CREATE_INTERACTION_RESPONSE';
	const REST_GET_ORIGINAL_INTERACTION_RESPONSE = 'REST_GET_ORIGINAL_INTERACTION_RESPONSE';
	const REST_EDIT_ORIGINAL_INTERACTION_RESPONSE = 'REST_EDIT_ORIGINAL_INTERACTION_RESPONSE';
	const REST_DELETE_ORIGINAL_INTERACTION_RESPONSE = 'REST_DELETE_ORIGINAL_INTERACTION_RESPONSE';
	const REST_CREATE_FOLLOWUP_MESSAGE = 'REST_CREATE_FOLLOWUP_MESSAGE';
	const REST_GET_FOLLOWUP_MESSAGE = 'REST_GET_FOLLOWUP_MESSAGE';
	const REST_EDIT_FOLLOWUP_MESSAGE = 'REST_EDIT_FOLLOWUP_MESSAGE';
	const REST_DELETE_FOLLOWUP_MESSAGE = 'REST_DELETE_FOLLOWUP_MESSAGE';
	const REST_INTERACTION_RESPONSE = 'REST_INTERACTION_RESPONSE';
}