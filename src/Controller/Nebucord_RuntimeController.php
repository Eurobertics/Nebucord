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

namespace Nebucord\Controller;

use Nebucord\Base\Nebucord_Controller_Abstract;
use Nebucord\Base\Nebucord_Status;
use Nebucord\Base\Nebucord_Timer;
use Nebucord\Events\Nebucord_ActionTable;
use Nebucord\Events\Nebucord_EventTable;
use Nebucord\Factories\Nebucord_Model_Factory;
use Nebucord\Http\Nebucord_WebSocket;
use Nebucord\Models\Nebucord_Model_GWReady;
use Nebucord\NebucordREST;

/**
 * Class Nebucord_RuntimeController
 *
 * The core of Nebucord, this one keeps track of read timing, runtime state, event messaging and actions
 * wich follow of the events.
 *
 * @package Nebucord\Controller
 */
class Nebucord_RuntimeController extends Nebucord_Controller_Abstract {

    /** @var Nebucord_Status $_runstate The current runstate of Nebucord. */
    private $_runstate;

    /** @var Nebucord_WebSocket The websocket client instance for interacting with the Discord Gateway. */
    private $_wscon;

    /** @var Nebucord_EventController $_evtctrl The event controller to keep track on the events and representing models. */
    private $_evtctrl;

    /** @var Nebucord_EventTable $_evttbl The event table wich keeps track on the callbacks to called on an event. */
    private $_evttbl;

    /** @var Nebucord_ActionTable $_acttbl The action table wich keeps track on the action locally executed on arrival of a gateway event. */
    private $_acttbl;

    /** @var Nebucord_ActionController $_actctrl Controls the actions to be executed locally. */
    private $_actctrl;

    /** @var array $_params User parameter wich are determine bot token, user access i. e. . */
    private $_params;

    /** @var string $_session The session of the connection from the gateway. */
    private $_session_id;

    /** @var integer $_botuserid The user id of the connected bot to. */
    private $_botuserid;

    /** @var string $_botusername The user name of the bot. */
    private $_botusername;

    /**
     * Nebucord_RuntimeController constructor.
     *
     * Sets up all other event- and action tables and starts itself.
     *
     * @param Nebucord_WebSocket $nebuwsconobj The websocket client instance for communications.
     * @param Nebucord_EventTable $evttbl Event table for external callbacks.
     * @param Nebucord_ActionTable $acttbl Action table for internal executions.
     * @param array $params User given parameters.
     */
    public function __construct(Nebucord_WebSocket &$nebuwsconobj, &$evttbl, &$acttbl, array $params = array()) {
        parent::__construct();
        $this->_wscon = $nebuwsconobj;
        $this->_evttbl = $evttbl;
        $this->_acttbl = $acttbl;
        $this->_params = $params;
    }

    /**
     * Nebucord_RuntimeController constructor.
     *
     * Cleans up itselfs.
     */
    public function __destruct() {
        parent::__destruct();
    }

    /**
     * Sets runtime state of Nebucord
     *
     * If the runtime state change (on exit i. E.), this can be changed with this method.
     *
     * @param integer $runtimestate The new runtime state.
     */
    public function setRuntimeState($runtimestate) {
        if(is_int($runtimestate)) {
            $this->_runstate = $runtimestate;
        }
    }

    /**
     * Gets runtime state of Nebucord
     *
     * This returns the current runtime status.
     *
     * @return Nebucord_Status The current runtime status.
     */
    public function getRuntimeState() {
        return $this->_runstate;
    }

    /**
     * Prepares the main loop.
     *
     * Starts (by setting the runtime to 'run') and stores other controller for action and event controlling and
     * starts the main loop.
     */
    public function start() {
        $this->_runstate = Nebucord_Status::NC_RUN;
        if(!$this->_wscon->connect()) {
            $this->_runstate = Nebucord_Status::NC_EXIT;
        }
        $this->_evtctrl = new Nebucord_EventController($this->_evttbl);
        $this->_actctrl = new Nebucord_ActionController($this->_acttbl, $this->_params);
        \Nebucord\Logging\Nebucord_Logger::info("Nebucord set up, entering main loop...", "nebucord.log");
        $this->mainLoop();
    }

    /**
     * The Nebucord man loop
     *
     * After setting everything up, Nebucord goes into the runstate of the main loop, where it's listening for
     * events and sendind events back to user callbacks or the gateway in case of internal action.
     */
    private function mainLoop() {
        $intervaltime = 0;
        $currentsequence = 0;
        $timer = new Nebucord_Timer(1000);

        $timer->startTimer();
        while($this->_runstate) {
            $message = $this->_wscon->soReadAll();
            if($message == -1) {
                \Nebucord\Logging\Nebucord_Logger::error("Error reading event from gateway, exiting...", "nebucord.log");
                $this->_runstate = Nebucord_Status::NC_EXIT;
            }

            if(strlen($message) > 0) {
                $this->_evtctrl->readEvent($message);
                $oInEvent = $this->_evtctrl->dispatchEventLocal();
                if(isset($oInEvent->heartbeat_interval)) { $intervaltime = $oInEvent->heartbeat_interval; }
                if(isset($oInEvent->s)) { $currentsequence = $oInEvent->s; }
                if($oInEvent instanceof Nebucord_Model_GWReady) {
                    $this->_session_id = $oInEvent->session_id;
                    $this->_botuserid = $oInEvent->user['id'];
                    $this->_botusername = $oInEvent->user['username'];
                    $this->_actctrl->setBotId($this->_botuserid);

                    $rest = new NebucordREST(['token' => $this->_params['token']]);
                    $readyconfirmmsg = array("title" => Nebucord_Status::CLIENTBROWSER." (v. ".Nebucord_Status::VERSION.")", "description" => "Bot details:", "fields" => array(array("name" => "Bot name", "value" => $this->_botusername, "inline" => false), array("name" => "Bot Snowflake ID", "value" => $this->_botuserid, "inline" => false)));
                    for($i = 0; $i < count($this->_params['ctrlusr']); $i++) {
                        $dmch = $rest->user->createDM($this->_params['ctrlusr'][$i]);
                        $rest->channel->createMessage($dmch->id, "Bot is ready and online.", $readyconfirmmsg);
                    }
                    unset($dmch);
                    unset($rest);
                    unset($readyconfirmmsg);
                }

                $this->_actctrl->setInternalAction($oInEvent);
                $oOutEvent = $this->_actctrl->getInternalActionEvent();
                if($oOutEvent) {
                    if(get_class($oOutEvent) == "Nebucord\Models\Nebucord_Model_RESTMessage") {
                        $rest = new NebucordREST(['token' => $this->_params['token']]);
                        
                        if($oOutEvent->embed != null) {
                            $rest->channel->createMessageObject($oOutEvent);
                        } else {
                            $rest->channel->createMessageObject($oOutEvent);
                        }
                        unset($rest);
                    } else {
                        $sendbytes = $this->_wscon->soWriteAll($this->prepareJSON($oOutEvent->toArray()));
                        if($sendbytes == -1) {
                            \Nebucord\Logging\Nebucord_Logger::error("Can't write event to gateway, exiting...", "nebucord.log");
                            $this->_runstate = Nebucord_Status::NC_EXIT;
                        }
                        if ($oOutEvent->status == "offline") {
                            $this->setRuntimeState(Nebucord_Status::NC_EXIT);
                            $rest = new NebucordREST(['token' => $this->_params['token']]);
                            $shutdownmsg = array("title" => Nebucord_Status::CLIENTBROWSER." (v. ".Nebucord_Status::VERSION.")", "description" => "Bot details:", "fields" => array(array("name" => "Bot name", "value" => $this->_botusername, "inline" => false), array("name" => "Bot Snowflake ID", "value" => $this->_botuserid, "inline" => false)));
                            for($i = 0; $i < count($this->_params['ctrlusr']); $i++) {
                                $dmch = $rest->user->createDM($this->_params['ctrlusr'][$i]);
                                $rest->channel->createMessage($dmch->id, "Bot ending process and exits, shutdown scheduled.", $shutdownmsg);
                            }
                            unset($dmch);
                            unset($rest);
                            unset($readyconfirmmsg);
                        }
                    }
                }
            }

            if($intervaltime > 0) {
                if($timer->getTime() > $intervaltime) {
                    $timer->reStartTimer();
                    $oHeartbeat = Nebucord_Model_Factory::create(Nebucord_Status::OP_HEARTBEAT);
                    $oHeartbeat->d = $currentsequence;
                    \Nebucord\Logging\Nebucord_Logger::info("Sending heartbeat...");
                    $sendbytes = $this->_wscon->soWriteAll($this->prepareJSON($oHeartbeat->toArray()));
                    if($sendbytes == -1) {
                        \Nebucord\Logging\Nebucord_Logger::error("Can't write heartbeat message to gateway, exiting...", "nebucord.log");
                    }
                }
            }
        }
    }
}
