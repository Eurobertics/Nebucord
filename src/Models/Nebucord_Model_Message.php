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
 * Class Nebucord_Model_Message
 *
 * A model representing a message object from a gateway.
 *
 * For more information regarding the properties of this model @see https://discordapp.com/developers/docs/intro
 *
 * @package Nebucord\Models
 */
class Nebucord_Model_Message extends Nebucord_Model {

    protected $_id;
    protected $_channel_id;
    protected $_author;
    protected $_content;
    protected $_timestamp;
    protected $_edited_timestamp;
    protected $_tts;
    protected $_mention_everyone;
    protected $_mentions;
    protected $_mention_roles;
    protected $_attachements;
    protected $_embeds;
    protected $_reactions;
    protected $_nonce;
    protected $_pinned;
    protected $_webhook_id;
    protected $_type;
    protected $_activity;
    protected $_application;

    /**
     * Nebucord_Model_Message constructor.
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
     * Nebucord_Model_Message destructor.
     *
     * Cleas everything up on end.
     */
    public function __destruct() {
        parent::__destruct();
    }
}