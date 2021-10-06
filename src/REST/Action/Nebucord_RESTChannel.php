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
use Nebucord\REST\Base\Nebucord_RESTAction;
use Nebucord\Factories\Nebucord_Model_Factory;

/**
 * Class Nebucord_RESTChannel
 *
 * Channel ressource action. This includes all requests regarding a channel.
 *
 * @package Nebucord\REST\Action
 */
class Nebucord_RESTChannel extends Nebucord_RESTAction {

    /**
     * Nebucord_RESTChannel constructor.
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
     * Nebucord_RESTChannel destructor.
     *
     * After the request is send and an answer is received, cleans everything up.
     */
    public function __destruct() {
    	parent::__destruct();
    }

    /**
     * Sends a message.
     *
     * Sends a message to the given channel.
     *
     * @param integer $channel The snowflake of the target channel.
     * @param string $message The message to be send.
     * @param array $embed If it should be a rich text message, this array is used @see https://discordapp.com/developers/docs/resources/channel#embed-object
     * @return \Nebucord\Models\Nebucord_Model The message model with the answer from the REST gateway.
     */
    public function createMessage($channel, $message, array $embed = array()) {
        $oReqMessageModel = Nebucord_Model_Factory::createREST(Nebucord_Status::REST_CREATE_MESSAGE);
        $oReqMessageModel->populate(["content" => $message, "channelid" => $channel, "embed" => $embed]);
        $this->_httpclient->setParams($oReqMessageModel);
        $res = $this->_httpclient->execute();
        $oMessageModel = Nebucord_Model_Factory::create();
        $oMessageModel->populate($res);
        return $oMessageModel;
    }

    /**
     * Sends a message from a message object.
     *
     * Same as createMessage(), but this method accpets a Nebucord_RESTMessage model instead of
     * individual parameters.
     *
     * @param \Nebucord\Models\Nebucord_Model_REST $oMessage The rest message request model wich contains the message send data.
     * @return \Nebucord\Models\Nebucord_Model The message model with the answer from the REST gateway.
     */
    public function createMessageObject(\Nebucord\Models\Nebucord_Model_REST $oMessage) {
        $this->_httpclient->setParams($oMessage);
        $res = $this->_httpclient->execute();
        $oMessageModel = Nebucord_Model_Factory::create();
        $oMessageModel->populate($res);
        return $oMessageModel;
    }

    /**
     * Lists messages from a channel.
     *
     * Get an array of message objects from a channel.
     *
     * @param integer $channelid The channel id for the message to be selected.
     * @param integer $limit The limit of message to receive from the channel.
     * @param integer|null $around The snowflake message id of a message where to get other messages around from.
     * @param integer|null $before The snowflake message id of a message to receive message before this (message) id.
     * @param integer|null $after The snowflake message id of a message to receive message after this (message) id.
     * @return array|\Nebucord\Models\Nebucord_Model The array with the message objects from the given channel.
     */
    public function getChannelMessages($channelid, $limit = 50, $around = null, $before = null, $after = null) {
        $oChannelMsgReq = Nebucord_Model_Factory::createREST(Nebucord_Status::REST_CHANNEL_ALL_MESSAGES);
        $oChannelMsgReq->channelid = $channelid;
        $oChannelMsgReq->limit = $limit;
        $oChannelMsgReq->around = $around;
        $oChannelMsgReq->before = $before;
        $oChannelMsgReq->after = $after;
        $this->_httpclient->setParams($oChannelMsgReq);
        $res = $this->_httpclient->execute();
        $return_ar = array();
        for($i = 0; $i < count($res); $i++) {
            $oMessageModel = Nebucord_Model_Factory::create();
            $oMessageModel->populate($res[$i]);
            $return_ar[] = $oMessageModel;
            unset($oMessageModel);
        }
        return $return_ar;
    }
}
