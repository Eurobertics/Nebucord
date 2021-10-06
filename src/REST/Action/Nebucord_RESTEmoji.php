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
use Nebucord\REST\Base\Nebucord_RESTAction;

/**
 * Class Nebucord_RESTEmoji
 *
 * Emoji ressource action. This includes all requests regarding emojis.
 *
 * @package Nebucord\REST\Action
 */
class Nebucord_RESTEmoji extends Nebucord_RESTAction {

    /**
     * Nebucord_RESTEmoji constructor.
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
     * Nebucord_RESTEmoji destructor.
     *
     * After the request is send and an answer is received, cleans everything up.
     */
    public function __destruct() {
        parent::__destruct();
    }

    /**
     * Lists all guild emojis.
     *
     * Returns an array of all emojis belonging to the given guild.
     *
     * @param integer $guildid The guild id to retrive the emojis from.
     * @return array The array with emoji models from the guild
     */
    public function listGuildEmojis($guildid) {
        $oReqEmojiModel = Nebucord_Model_Factory::createREST(Nebucord_Status::REST_GUILD_GET_ALL_EMOJIS);
        $oReqEmojiModel->guildid = $guildid;
        $this->_httpclient->setParams($oReqEmojiModel);
        $res = $this->_httpclient->execute();
        $ret_ar = array();
        for($i = 0; $i < count($res); $i++) {
            $oEmojiModel = Nebucord_Model_Factory::create();
            $oEmojiModel->populate($res[$i]);
            $ret_ar[] = $oEmojiModel;
            unset($oEmojiModel);
        }
        return $ret_ar;
    }

    /**
     * Returns an specific emoji from a guild.
     *
     * Returns the emoji identified by the given id from the given guild.
     *
     * @param integer $guildid The guild id to load the emoji from.
     * @param integer $emojiid The id of the emoji to load.
     * @return \Nebucord\Models\Nebucord_Model The emoji model of the wanted emoji.
     */
    public function getGuildEmoji($guildid, $emojiid) {
        $oReqEmojiModel = Nebucord_Model_Factory::createREST(Nebucord_Status::REST_GUILD_GET_EMOJI);
        $oReqEmojiModel->guildid = $guildid;
        $oReqEmojiModel->emojiid = $emojiid;
        $this->_httpclient->setParams($oReqEmojiModel);
        $res = $this->_httpclient->execute();
        $oEmojiModel = Nebucord_Model_Factory::create();
        $oEmojiModel->populate($res);
        return $oEmojiModel;
    }
}