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
use Nebucord\Models\Nebucord_Model;
use Nebucord\REST\Base\Nebucord_RESTAction;

/**
 * Class Nebucord_RESTUser
 *
 * User ressource action. This includes all requests regarding a user or user dm's.
 *
 * @package Nebucord\REST\Action
 */
class Nebucord_RESTUser extends Nebucord_RESTAction {

    /**
     * Nebucord_RESTUser constructor.
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
     * Nebucord_RESTUser destructor.
     *
     * After the request is send and an answer is received, cleans everything up.
     */
    public function __destruct() {
        parent::__destruct();
    }

    /**
     * Craete a DM channel.
     *
     * Creates a DM chennal with the given recipient snowflake id. Returns an channel model object for the created
     * channel id for sending message direclty to a recipient (DM).
     *
     * @param integer $recipientid Recipient ID to create the dm channel with.
     * @return \Nebucord\Models\Nebucord_Model The channel model on success with the dm channel data.
     */
    public function createDM($recipientid) {
        $oReqChannelModel = Nebucord_Model_Factory::createREST(Nebucord_Status::REST_USER_CREATE_DM);
        $oReqChannelModel->recipient_id = $recipientid;
        $this->_httpclient->setParams($oReqChannelModel);
        $res = $this->_httpclient->execute();
        $oChannelModel = Nebucord_Model_Factory::create();
        $oChannelModel->populate($res);
        return $oChannelModel;
    }

    /**
     * Returns user data.
     *
     * Get information about the given user id (snowflake id).
     *
     * @param integer $userid The user id to fetch information for.
     * @return Nebucord_Model The model representation of the fetched user.
     */
    public function getUser($userid) {
        $oReqUserModel = Nebucord_Model_Factory::createREST(Nebucord_Status::REST_GET_USER);
        $oReqUserModel->userid = $userid;
        $this->_httpclient->setParams($oReqUserModel);
        $res = $this->_httpclient->execute();
        $oUserModel = Nebucord_Model_Factory::create();
        $oUserModel->populate($res);
        return $oUserModel;
    }
}
