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
 * Class Nebucord_RESTAuditLogs
 *
 * This is for getting audit logs from a guild.
 *
 * @package Nebucord\REST\Action
 */
class Nebucord_RESTAuditLogs extends Nebucord_RESTAction {

    /**
     * Nebucord_RESTAuditLogs constructor.
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
     * Nebucord_RESTAuditLogs destructor.
     *
     * After the request is send and an answer is received, cleans everything up.
     */
    public function __destruct() {
        parent::__destruct();
    }

    public function getGuildAuditLogs($guildid, $userid = 0, $action_type = 0, $before = 0, $limit = 50) {
        $oReqAuditLogsModel = Nebucord_Model_Factory::createREST(Nebucord_Status::REST_GUILD_GET_AUDIT_LOGS);
        $oReqAuditLogsModel->guildid = $guildid;
        if($userid > 0) {
            $oReqAuditLogsModel->userid = $userid;
        }
        if($action_type > Nebucord_Status::AUDIT_LOG_EVT_ALL) {
            $oReqAuditLogsModel->action_type = $action_type;
        }
        if($before > 0) {
            $oReqAuditLogsModel->before = $before;
        }
        $oReqAuditLogsModel->limit = $limit;
        $this->_httpclient->setParams($oReqAuditLogsModel);
        $res = $this->_httpclient->execute();
        $oAuditLogsModel = Nebucord_Model_Factory::create();
        $oAuditLogsModel->populate($res);
        return $oAuditLogsModel;
    }
}
