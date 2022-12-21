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
use Nebucord\Models\Nebucord_Model;
use Nebucord\Models\Nebucord_Model_REST;
use Nebucord\NebucordREST;
use Nebucord\REST\Base\Nebucord_RESTStatus;

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

    // /** @var string $_session The session of the connection from the gateway. */
    //private $_session_id;

    /** @var integer $_botuserid The user id of the connected bot to. */
    private $_botuserid;

    /** @var string $_botusername The user name of the bot. */
    private $_botusername;

    /** @var integer $_reconnect_tries The current tries for reconnecting. */
    private $_reconnect_tries = 1;

    /** @var NebucordREST $_rest Nebucord REST api object. */
    private $_rest;

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

        $this->_reconnect_tries = 0;
    }

    /**
     * Nebucord_RuntimeController constructor.
     *
     * Cleans up itselfs.
     */
    public function __destruct() {
        parent::__destruct();
        $this->_reconnect_tries = 0;
    }

    /**
     * Sets runtime state of Nebucord
     *
     * If the runtime state change (on exit i. E.), this can be changed with this method.
     *
     * @param integer $runtimestate The new runtime state.
     */
    public function setRuntimeState($runtimestate) {
        $this->_runstate = $runtimestate;
        if($runtimestate == Nebucord_Status::NC_RECONNECT) {
            $this->resume();
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
        \Nebucord\Logging\Nebucord_Logger::info("Nebucord set up, entering main loop...");
        $this->_rest = new NebucordREST(['token' => $this->_params['token']]);
        $this->mainLoop();
    }

    /**
     * Resume broken connection.
     *
     * If a connection breaks, this method is called to reconnect and try initiate listen status for missed events.
     */
    private function resume() {
        \Nebucord\Logging\Nebucord_Logger::warn("Reconnect with try ".$this->_reconnect_tries." of ".$this->_params['wsretries']."...");
        if($this->_reconnect_tries >= $this->_params['wsretries']) {
            \Nebucord\Logging\Nebucord_Logger::infoImportant("Max reconnection tries reached, giving up and exiting...");
            $this->setRuntimeState(Nebucord_Status::NC_EXIT);
            return;
        }
        sleep(mt_rand(1, 4));
        \Nebucord\Logging\Nebucord_Logger::infoImportant("Try to reconnect...");
        if(!$this->_wscon->reconnect()) {
            $this->resume();
            $this->_reconnect_tries++;
        }
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
        $timer->startTimer(1);
        while($this->_runstate > Nebucord_Status::NC_EXIT) {
            $message = $this->_wscon->soReadAll();
            if($message[0] == -1) {
                \Nebucord\Logging\Nebucord_Logger::error("Error reading event from gateway, disconnect and try to reconnect...");
                $this->botFailureMessage(serialize($message), Nebucord_Status::NC_RECONNECT);
                $this->setRuntimeState(Nebucord_Status::NC_RECONNECT);
                continue;
                //break;
            }
            if($message[0] == -2) {
                \Nebucord\Logging\Nebucord_Logger::error("Gateway respond with error: ".$message[1]);
                \Nebucord\Logging\Nebucord_Logger::error("Gateway closes connection, exiting...");
                if(((int)substr($message[1], 0, 4) >= 1000 && (int)substr($message[1], 0, 4) <= 1015) || ((int)substr($message[1], 0, 4) >= 4000 && (int)substr($message[1], 0, 4) <= 4014)) {
                    $closecode = substr($message[1], 0, 4);
                    $whichaction = Nebucord_Status::NC_RECONNECT;
                    switch($closecode) {
                        case "4004":
                        case "4010":
                        case "4011":
                        case "4012":
                        case "4013":
                        case "4014": $whichaction = Nebucord_Status::NC_EXIT; break;
                        default: $whichaction = Nebucord_Status::NC_RECONNECT; break;
                    }
                    $this->botFailureMessage("Websocket close code '".$closecode."' received.", $whichaction);
                    \Nebucord\Logging\Nebucord_Logger::warn("Websocket close code received (".$closecode."). Nebucord state code: ".$whichaction);
                    $this->setRuntimeState(Nebucord_Status::NC_RECONNECT);
                } else {
                    $this->botFailureMessage(serialize($message), Nebucord_Status::NC_EXIT);
                    \Nebucord\Logging\Nebucord_Logger::error("Websocket code ".$message[1]." received, seems unnatural, exiting!");
                    $this->setRuntimeState(Nebucord_Status::NC_EXIT);
                }
                continue;
                //break;
            }

            if($message[0] == 0 && !empty($message[1])) {
                if(!$this->_evtctrl->readEvent($message[1])) {
                    \Nebucord\Logging\Nebucord_Logger::error("Could not decode message from gateway, API or connection may broken (received NULL). Ignoring message and reconnect for resume!");
                    $this->botFailureMessage(serialize($message), Nebucord_Status::NC_RECONNECT);
                    $this->setRuntimeState(Nebucord_Status::NC_RECONNECT);
                    //continue;
                }
                $oInEvent = $this->_evtctrl->dispatchEventLocal();
                if(isset($oInEvent->heartbeat_interval)) { $intervaltime = $oInEvent->heartbeat_interval; }
                if(!is_null($oInEvent->s) && $this->_runstate == Nebucord_Status::NC_RUN) { $currentsequence = $oInEvent->s; $this->_actctrl->setSequence($oInEvent->s); }
                if($oInEvent->t == Nebucord_Status::GWEVT_READY) {
                    $this->_wscon->setNewWSConnectURL($oInEvent->resume_gateway_url);
                    $this->botStartup($oInEvent);
                }

                $this->_actctrl->setInternalAction($oInEvent, $this->_runstate);
                $oOutEvent = $this->_actctrl->getInternalActionEvent();
                if($oOutEvent && $oOutEvent->op != Nebucord_Status::OP_HEARTBEAT_ACK) {
                    if(get_class($oOutEvent) == "Nebucord\Models\Nebucord_Model_REST") {
                        $this->botMessage($oOutEvent);
                        if($oOutEvent->reboot) {
                            $this->setRuntimeState(Nebucord_Status::NC_RECONNECT);
                            unset($oOutEvent->reboot);
                        }
                    } else {
                        if($oOutEvent->t == Nebucord_Status::GWEVT_RESUMED) { $this->setRuntimeState(Nebucord_Status::NC_RUN); $timer->reStartTimer(); $timer->reStartTimer(1); $this->_reconnect_tries = 0; }
                        else { $sendbytes = $this->_wscon->soWriteAll($this->prepareJSON($oOutEvent->toArray())); }
                        if($sendbytes == -1) {
                            \Nebucord\Logging\Nebucord_Logger::error("Can't write event to gateway, exiting...");
                            $this->botFailureMessage("Can't write event to gateway, exiting...", Nebucord_Status::NC_RECONNECT);
                            $this->setRuntimeState(Nebucord_Status::NC_RECONNECT);
                            continue;
                        }
                        if ($oOutEvent->status == "offline") {
                            $this->setRuntimeState(Nebucord_Status::NC_EXIT);
                            $this->botShutdown();
                        }
                    }
                } elseif($oOutEvent && $oOutEvent->op == Nebucord_Status::OP_HEARTBEAT_ACK) {
                    $timer->reStartTimer(1);
                }
            }

            if($timer->getTime(1) > ($intervaltime * 1.5) && $this->_runstate == Nebucord_Status::NC_RUN) {
                \Nebucord\Logging\Nebucord_Logger::error("Did not receive any heartbeat response from gateway. Connection broken? Try to reconnect...");
                $this->botFailureMessage("Did not receive any heartbeat response from gateway. Connection broken? Try to reconnect...", Nebucord_Status::NC_RECONNECT);
                $this->setRuntimeState(Nebucord_Status::NC_RECONNECT);
            }

            if($intervaltime > 0 && $this->_runstate == Nebucord_Status::NC_RUN) {
                if($timer->getTime() > $intervaltime) {
                    $timer->reStartTimer();
                    $oHeartbeat = Nebucord_Model_Factory::create(Nebucord_Status::OP_HEARTBEAT);
                    $oHeartbeat->d = $currentsequence;
                    \Nebucord\Logging\Nebucord_Logger::info("Sending heartbeat...");
                    $sendbytes = $this->_wscon->soWriteAll($this->prepareJSON($oHeartbeat->toArray()));
                    if($sendbytes == -1) {
                        \Nebucord\Logging\Nebucord_Logger::error("Can't write heartbeat message to gateway, exiting...");
                        $this->botFailureMessage("Can't write heartbeat message to gateway, exiting...", Nebucord_Status::NC_RECONNECT);
                        $this->setRuntimeState(Nebucord_Status::NC_RECONNECT);
                        //break;
                    }
                }
            }
        }
    }

    /**
     * Starts the bot
     *
     * Get inital parameters from gateway and sends a short startup message to the bot admins.
     *
     * @param Nebucord_Model $evt The returned GatewayReady event with initial parameters.
     */
    private function botStartup(Nebucord_Model $evt) {
        //$this->_session_id = $evt->session_id;
        $this->_actctrl->setSession($evt->session_id);
        $this->_botuserid = $evt->user['id'];
        $this->_botusername = $evt->user['username'];
        $this->_actctrl->setBotId($this->_botuserid);

        $readyconfirmmsg = array("title" => Nebucord_Status::CLIENTBROWSER." (v. ".Nebucord_Status::VERSION.")", "description" => "Bot details:", "fields" => array(array("name" => "Bot name", "value" => $this->_botusername, "inline" => false), array("name" => "Bot Snowflake ID", "value" => $this->_botuserid, "inline" => false)));
        for($i = 0; $i < count($this->_params['ctrlusr']); $i++) {
            $dmch = $this->_rest->createRESTExecutor()->executeFromArray(Nebucord_RESTStatus::REST_CREATE_DM, ['recipient_id' => $this->_params['ctrlusr'][$i]]);
            $this->_rest->createRESTExecutor()->executeFromArray(Nebucord_RESTStatus::REST_CREATE_MESSAGE, [
                'channel_id' => $dmch->id,
                'content' => "Bot is ready and online.",
                'embed' => $readyconfirmmsg
            ]);
        }
        unset($dmch);
        unset($readyconfirmmsg);
    }

    /**
     * Shuts the bot down
     *
     * Sends a shutdown notification to the bot admins.
     */
    private function botShutdown() {
        $shutdownmsg = array("title" => Nebucord_Status::CLIENTBROWSER." (v. ".Nebucord_Status::VERSION.")", "description" => "Bot details:", "fields" => array(array("name" => "Bot name", "value" => $this->_botusername, "inline" => false), array("name" => "Bot Snowflake ID", "value" => $this->_botuserid, "inline" => false)));
        for($i = 0; $i < count($this->_params['ctrlusr']); $i++) {
            $dmch = $this->_rest->createRESTExecutor()->executeFromArray(Nebucord_RESTStatus::REST_CREATE_DM, ['recipient_id' => $this->_params['ctrlusr'][$i]]);
            $this->_rest->createRESTExecutor()->executeFromArray(Nebucord_RESTStatus::REST_CREATE_MESSAGE, [
                'channel_id' => $dmch->id,
                'content' => "Bot ending process and exits, shutdown scheduled.",
                'embed' => $shutdownmsg
            ]);
        }
        unset($dmch);
        unset($shutdownmsg);
    }

    /**
     * Sends a message from bot
     *
     * Sends a message from the bot.
     *
     * @param \Nebucord\Models\Nebucord_Model $evt The message object.
     */
    private function botMessage(\Nebucord\Models\Nebucord_Model $evt) {
        $oRESTRequestModel = Nebucord_Model_Factory::createREST(Nebucord_RESTStatus::REST_CREATE_MESSAGE);
        $oRESTRequestModel->populate($evt->toArray());
        $this->_rest->createRESTExecutor()->execute(Nebucord_RESTStatus::REST_CREATE_MESSAGE, $oRESTRequestModel);
    }

    /**
     * Sends a error notice
     *
     * If Nebucord receives an error from the websocket gateway, a message is sent to all
     * controls user as a notice.
     *
     * @param string $errmsg The errorstring Nebucord received
     * @param int $state The new state Nebucord enters based on the error
     */
    private function botFailureMessage(string $errmsg, int $state)
    {
        if(!$this->_params['dmonfailures']) { return; }
        $errormsg = array(
            "title" => Nebucord_Status::CLIENTBROWSER." (v. ".Nebucord_Status::VERSION.")",
            "description" => "Nebucord API error:",
            "fields" => array(
                array(
                    "name" => "Bot name",
                    "value" => $this->_botusername,
                    "inline" => false),
                array(
                    "name" => "Errormessage",
                    "value" => $errmsg,
                    "inline" => false),
                array(
                    "name" => "New state",
                    "value" => $state,
                    "inline" => false
                )
            )
        );
        for($i = 0; $i < count($this->_params['ctrlusr']); $i++) {
            $dmch = $this->_rest->createRESTExecutor()->executeFromArray(Nebucord_RESTStatus::REST_CREATE_DM, ['recipient_id' => $this->_params['ctrlusr'][$i]]);
            $this->_rest->createRESTExecutor()->executeFromArray(Nebucord_RESTStatus::REST_CREATE_MESSAGE, [
                'channel_id' => $dmch->id,
                'content' => "Nebucord API encountered an error!",
                'embed' => $errormsg
            ]);
        }
        unset($dmch);
        unset($errormsg);
    }
}
