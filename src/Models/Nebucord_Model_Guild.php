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

namespace Nebucord\Models;

/**
 * Class Nebucord_Model_Guild
 *
 * A model representing a guild object from a gateway.
 *
 * For more information regarding the properties of this model @see https://discordapp.com/developers/docs/intro
 *
 * @package Nebucord\Models
 */
class Nebucord_Model_Guild extends Nebucord_Model {

    protected $_id;
    protected $_name;
    protected $_icon;
    protected $_splash;
    protected $_owner;
    protected $_owner_id;
    protected $_permissions;
    protected $_region;
    protected $_afk_channel_id;
    protected $_afk_timeour;
    protected $_embed_enabled;
    protected $_embed_channel_id;
    protected $_verification_level;
    protected $_default_message_notifications;
    protected $_explicit_content_filter;
    protected $_roles;
    protected $_emojis;
    protected $_features;
    protected $_mfa_level;
    protected $_application_id;
    protected $_widget_enabled;
    protected $_widget_channel_id;
    protected $_system_channel_id;
    protected $_joined_at;
    protected $_large;
    protected $_unavailable;
    protected $_member_count;
    protected $_voice_states;
    protected $_members;
    protected $_channels;
    protected $_presences;

    /**
     * Nebucord_Model_Guild constructor.
     * Sets everything up and transfers the basic event data to the abstract class for storing through the
     * base model class (Nebucord_Model).
     *
     * @param \Nebucord\Base\Nebucord_Status $op The OP code of an event.
     * @param \Nebucord\Base\Nebucord_Status $event The event from the gateway (mostly with OP code 0).
     */
    public function __construct($op = null, $event = null) {
        parent::__construct($op, $event);
    }

    /**
     * Nebucord_Model_Guild destructor.
     *
     * Cleas everything up on end.
     */
    public function __destruct() {
        parent::__destruct();
    }
}