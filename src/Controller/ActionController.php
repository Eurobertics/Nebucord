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

use Nebucord\Base\AbstractController;
use Nebucord\Base\StatusList;
use Nebucord\Interfaces\IActionTable;
use Nebucord\Factories\ModelFactory;
use Nebucord\Models\Model;

/**
 * Class ActionController
 *
 * Nebucord has build in actions, wich can be extended beside the event table, if needed. This class controls
 * the action and creates the models wich are needed to send to the server.
 *
 * There are some actions wich can't be overwritten by an action table such as OP_HELLO or OP_IDENTFY.
 *
 * @package Nebucord\Controller
 */
class ActionController extends AbstractController {

    /** @var object|Model $_inevent The incoming event model from the Discord gateway. */
    private $_inevent;

    /** @var object|Model $_outevent The event model wich is in turn send to the gateway.  */
    private $_outevent;

    /** @var object|IActionTable Internal events (maybe overwritten) to perfom actions. */
    private $_acttbl;

    /** @var string The token parameter wich identifys the bot on OP_IDENTIFY. */
    private $_token;

    /** @var integer $_botuserid The user id of the bot. */
    private $_botuserid;

    /** @var array An array of user (snowflakes) wich can control the bot and the actions wich are set up here. */
    private $_ctrlusr;

    /** @var integer $_intents The bitmask of the intents to observe */
    private $_intents;

    /** @var integer The current state in wich the API is currently. */
    private $_state;

    /** @var integer The current connection sequence. */
    private $_sequence;

    /** @var string $_session The current session for the connection.*/
    private $_sessionid;

    /**
     * ActionController constructor.
     *
     * Creates and clears the basics of the controller.
     *
     * @param object|IActionTable $acttbl The action table to perfom actions.
     * @param array $params The parameter wich are given on Nebucord initialising (token, user, etc.).
     */
    public function __construct(&$acttbl, array $params = array()) {
        parent::__construct();
        $this->_inevent = null;
        $this->_outevent = null;
        $this->_acttbl = $acttbl;
        $this->_token = null;
        $this->_botuserid = 0;
        $this->_ctrlusr = array();
        $this->_intents = null;
        $this->_state = StatusList::NC_RUN;
        $this->_sequence = 0;
        $this->_sessionid = null;

        if(!empty($params)) {
            foreach($params as $key => $val) {
                $prop = "_".$key;
                if(property_exists($this, $prop)) {
                    $this->$prop = $val;
                }
            }
        }
    }

    /**
     * ActionController constructor.
     *
     * Cleans up all actions after disconnecting and ending.
     */
    public function __destruct() {
        parent::__destruct();
        $this->_inevent = null;
        $this->_outevent = null;
        $this->_acttbl = null;
        $this->_token = null;
        $this->_botuserid = 0;
        $this->_ctrlusr = array();
        $this->_intents = null;
        $this->_state = StatusList::NC_EXIT;
        $this->_sequence = 0;
        $this->_sessionid = null;
    }

    /**
     * Sets bot id.
     *
     * Sets the bot id of the connected bot to avoid interferending other bots wich are also connected on sending
     * commands.
     *
     * @param integer $id The bod id as snowflake, mostly received by the READY event.
     */
    public function setBotId($id) {
        $this->_botuserid = $id;
    }

    /**
     * Sets current session.
     *
     * Sets the current session id for the actual connection with the gateway.
     *
     * @param string $sessionid The current session id.
     */
    public function setSession($sessionid) {
        $this->_sessionid = $sessionid;
    }

    /**
     * Sets the current sequence.
     *
     * Sets the current connections sequence from the gateway.
     *
     * @param integer $sequence The acutal sequence.
     */
    public function setSequence($sequence) {
        $this->_sequence = $sequence;
    }

    /**
     * Sets internal action to be executed.
     *
     * Sets the action wich should be fired based on the incoming event from the gateway.
     * After that, it executes the selection of an action.
     *
     * @param object|Model $event The event model to determine the actions.
     * @param integer $state The current state in wich the API is currently.
     */
    public function setInternalAction(Model $event, $state = 1) {
        $this->_inevent = $event;
        $this->_state = $state;
        $this->selectInternalAction();
    }

    /**
     * Return an action to be fired.
     *
     * Based on the action table and the incoming gateway event, this method returns the calcualted model
     * with the answer action to be fired to the gateway.
     *
     * @return Model The model to send to the gateway wich the action to be performed.
     */
    public function getInternalActionEvent() {
        return $this->_outevent;
    }

    /**
     * Selects an action to be fired.
     *
     * Looks up the OP code and fires the needed action. In case of OP_CODE "0" this will be the dispatch action
     * wich in turn selects the GWEVT action.
     */
    private function selectInternalAction() {
        switch($this->_inevent->op) {
            case StatusList::OP_DISPATCH: $this->doDispatch(); break;
            case StatusList::OP_HELLO: $this->doIdentify(); break;
            case StatusList::OP_RECONNECT: $this->doReconnect(); break;
            case StatusList::OP_HEARTBEAT_ACK: $this->doHeartbeatACK(); break;
            default: $this->_inevent = null; $this->_outevent = null; break;
        }
    }

    /**
     * Dispatches an action if OP_CODE is "0".
     *
     * If OP_CODE is "0" then it was not a control event. In this case the GWEVT events are determined and executed.
     */
    private function doDispatch() {
        switch($this->_inevent->t) {
            case StatusList::GWEVT_RESUMED: $this->onResume(); break;
            case StatusList::GWEVT_MESSAGE_CREATE: $this->onCreateMessageCommand(); break;
            default: $this->_inevent = null; $this->_outevent = null; break;
        }
    }

    /**
     * Identifies a bot to the gateway.
     *
     * This internal action authenticates and identifies a bot to the Discord Gateway.
     */
    private function doIdentify() {
        if($this->_state == StatusList::NC_RECONNECT) {
            $this->_outevent = ModelFactory::create(StatusList::OP_RESUME);
            $this->_outevent->populate([
                'op' => StatusList::OP_RESUME,
                'd' => [
                        'token' => $this->_token,
                        'session_id' => $this->_sessionid,
                        'seq' => $this->_sequence
                    ]
                ]);
        } else {
            $this->_outevent = ModelFactory::create(StatusList::OP_IDENTIFY);
            $this->_outevent->populate([
                'op' => StatusList::OP_IDENTIFY,
                'd' =>
                    [
                        'token' => $this->_token,
                        'properties' =>
                            [
                                '$os' => StatusList::getOS(),
                                '$browser' => StatusList::getBrowser(),
                                '$device' => StatusList::getDevice()],
                                'compress' => false,
                                'presence' =>
                                    [
                                        'since' => null,
                                        'game' => null,
                                        'status' => 'online',
                                        'afk' => false
                                    ],
                        'intents' => $this->_intents
                    ]
            ]);
        }
    }

    private function doReconnect()
    {
        // Implement reconnect out event
    }

    private function onResume() {
        $this->_outevent = $this->_inevent;
        \Nebucord\Logging\MainLogger::infoImportant("All missing events received, reconnect completed.");
    }

    /**
     * Sends action on HeartbeatACK
     *
     * If we received a heartbeat ACK this action is fired. Since there is no need to do so, this is currently
     * not in use.
     */
    private function doHeartbeatACK() {
        \Nebucord\Logging\MainLogger::info("Receiving heartbeat ACK.");
        $this->_outevent = ModelFactory::create(StatusList::OP_HEARTBEAT_ACK);
        $this->_outevent->populate(['op' => StatusList::OP_HEARTBEAT_ACK]);
    }

    /**
     * Select action table action on getting a message.
     *
     * On a MESSAGE_CREATE event from the gateway, the action how it should respond is dertermined here.
     * The methods are described in the ActionTable class and can be overwritten.
     */
    private function onCreateMessageCommand() {
        $msg = $this->_inevent->content;
        if($this->authControlUser() && $this->checkBotID($msg)) {
            if (str_contains($msg, IActionTable::SHUTDOWN)) {
                \Nebucord\Logging\MainLogger::warn("Shutdown command received: " . $msg);
                $this->_outevent = $this->_acttbl->doShutdown($msg);
            } else if (str_contains($msg, IActionTable::SETSTATUS)) {
                \Nebucord\Logging\MainLogger::info("Setstatus command received: " . $msg);
                $this->_outevent = $this->_acttbl->setStatus($msg);
            } else if (str_contains($msg, IActionTable::GETHELP)) {
                \Nebucord\Logging\MainLogger::info("Help command received: " . $msg);
                $this->_outevent = $this->_acttbl->getHelp($msg);
                $this->_outevent->populate(['channel_id' => $this->_inevent->channel_id]);
            } else if (str_contains($msg, IActionTable::DOECHO)) {
                \Nebucord\Logging\MainLogger::info("Echo test command received: " . $msg);
                $this->_outevent = $this->_acttbl->doEcho($msg);
                $this->_outevent->populate(['channel_id' => $this->_inevent->channel_id]);
            } else if (str_contains($msg, IActionTable::DOSAY)) {
                \Nebucord\Logging\MainLogger::info("Do say command received: " . $msg);
                $this->_outevent = $this->_acttbl->doSay($msg);
                $this->_outevent->populate(['channel_id' => $this->_inevent->channel_id]);
            } else if (str_contains($msg, IActionTable::DOSTATUS)) {
                \Nebucord\Logging\MainLogger::info("Get status command received: " . $msg);
                $this->_outevent = $this->_acttbl->doStatus($msg);
                $this->_outevent->populate(['channel_id' => $this->_inevent->channel_id]);
            } else if (str_contains($msg, IActionTable::DOREBOOT)) {
                \Nebucord\Logging\MainLogger::warn("Reboot command received: " . $msg);
                \Nebucord\Logging\MainLogger::warn("Rebooting and reconnecting Nebucord to Discord...");
                $this->_outevent = $this->_acttbl->doRestart($msg);
                $this->_outevent->populate(['channel_id' => $this->_inevent->channel_id, 'reboot' => true]);
            } else if (str_contains($msg, IActionTable::DOLISTAPPCOMMANDS)) {
                \Nebucord\Logging\MainLogger::info("List application command received: " . $msg);
                $this->_outevent = $this->_acttbl->doListAppCommands(
                    $msg,
                    $this->_botuserid,
                    $this->_token,
                    $this->_inevent->guild_id
                );
                $this->_outevent->populate(['channel_id' => $this->_inevent->channel_id]);
            }
        }
        if (str_contains($msg, IActionTable::DOVERSION)) {
            \Nebucord\Logging\MainLogger::info("Do version command received: " . $msg);
            $this->_outevent = $this->_acttbl->doVersion($msg);
            $this->_outevent->populate(['channel_id' => $this->_inevent->channel_id]);
        }
    }

    /**
     * Authenticate a control user
     *
     * Security is important on controlling a bot. This checks if a user id (snowflake) has the ability to
     * perfom actions such as sending a command (MESSAGE_CREATE) to the bot.
     *
     * @return bool true if the user is whitelisted, otherwise false.
     */
    private function authControlUser() {
        $user2check = (isset($this->_inevent->author['id'])) ? $this->_inevent->author['id'] : false;
        if(!$user2check) { return false; }

        for($i = 0; $i < count($this->_ctrlusr); $i++) {
            if($user2check == $this->_ctrlusr[$i]) {
                return true;
            }
        }

        $this->_inevent = null;
        $this->_outevent = null;
        return false;
    }

    /**
     * Check if given bot id is correct.
     *
     * If there are more then one bots online, identification wich bot is targeted by a command is needed.
     * This checks if the bot id within the given command is the id of this running bot. On true, the command
     * will be executed, otherwise of course it will be ignored.
     *
     * @param string $message The command message where the id should be find.
     * @return bool If true the command is targeted to this bot, otherwise false.
     */
    private function checkBotID(&$message) {
        $returnstate = false;
        if(strpos($message, $this->_botuserid." ") !== false) {
            $message = str_replace($this->_botuserid." ", "", $message);
            $returnstate = true;
        }
        if(strpos($message, " ".$this->_botuserid) !== false) {
            $message = str_replace(" ".$this->_botuserid, "", $message);
            $returnstate = true;
        }
        if(strpos($message, "<@".$this->_botuserid."> ") !== false) {
            $message = str_replace("<@".$this->_botuserid, "> ", $message);
            $returnstate = true;
        }
        if(strpos($message, " <@".$this->_botuserid.">") !== false) {
            $message = str_replace(" <@".$this->_botuserid.">", "", $message);
            $returnstate = true;
        }
        return $returnstate;
    }
}
