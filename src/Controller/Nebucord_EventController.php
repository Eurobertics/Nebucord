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
use Nebucord\Events\Nebucord_EventTable;
use Nebucord\Factories\Nebucord_Model_Factory;
use Nebucord\Models\Nebucord_Model;

/**
 * Class Nebucord_EventController
 *
 * The event controller receives the events coming from the Discord gateway after the websocket/http client reads it.
 * Then this controller decides wich model is used for the incoming event and packs into it. Then it dispatches the event
 * model locally (for internal actions -> ActionController) and remote to the callback caller (if one is available,
 * EventTable).
 *
 * @package Nebucord\Controller
 */
class Nebucord_EventController extends Nebucord_Controller_Abstract {

    /** @var array $_eventmessage JSON decoded array of the last message from the gateway. */
    private $_eventmessage;

    /** @var Nebucord_EventTable The event table wich holds the callbacks wich should fired on an event. */
    private $_evttbl;

    /** @var integer $_lastopcode The last sended OP code from the gateway. */
    private $_lastopcode;

    /** @var integer $_lastsequence Last received gateway sequence if one. */
    private $_lastsequence;

    /** @var string $_lastevent The last gateway event sended by the gateway. */
    private $_lastevent;

    /** @var array $_lastmessage JSON decoded ['d'] message payload without event/op-code data. */
    private $_lastmessage;

    /** @var Nebucord_Model The model wich represents the event for returing back to the callback caller. */
    private $_model;

    /**
     * Nebucord_EventController constructor.
     *
     * Starts the event controller and stores a given event table to it.
     *
     * @param Nebucord_EventTable $evttbl
     */
    public function __construct(Nebucord_EventTable &$evttbl) {
        parent::__construct();
        $this->_evttbl = $evttbl;
    }

    /**
     * Nebucord_EventController destructor.
     *
     * Ends the EventController by deleting the eventmessage and cleans itself up.
     */
    public function __destruct() {
        parent::__destruct();
        $this->_eventmessage = "";
    }

    /**
     * Reads an incoming event.
     *
     * After the client reads a message from the Discord gateway, this method is called for parsing and storing it
     * to a local var. After that, the event model will be build by buildEventData().
     *
     * @param string $eventmessage A JSON message string from the Discord gateway.
     * @return bool If message could not decoded which result in NULL, returns false, true on success.
     */
    public function readEvent($eventmessage) {
        $this->_eventmessage = $this->parseJSON($eventmessage);
        if(is_null($this->_eventmessage)) {
            return false;
        }
        $this->buildEventData();
        return true;
    }

    /**
     * Builds the event data structure (model)
     *
     * Splits the message array from the gateway and builds the model out of the parsed structure.
     * The Model is choosen by the OP code and possible gateway event.
     */
    private function buildEventData() {
        $this->_lastopcode = (isset($this->_eventmessage['op'])) ? $this->_eventmessage['op'] : null;
        $this->_lastsequence = null;
        $this->_lastevent = null;
        $this->_lastmessage = (isset($this->_eventmessage['d'])) ? $this->_eventmessage['d'] : null;
        if($this->_lastopcode == Nebucord_Status::OP_DISPATCH) {
            $this->_lastevent = $this->_eventmessage['t'];
            $this->_lastsequence = $this->_eventmessage['s'];
            \Nebucord\Logging\Nebucord_Logger::info("Event received: ".$this->_lastevent." - Sequence: ".$this->_lastsequence);
        }

        $this->_model = Nebucord_Model_Factory::create($this->_lastopcode, $this->_lastevent);
        $this->_model->populate(['op' => $this->_lastopcode, 's' => $this->_lastsequence, 't' => $this->_lastevent, 'd' => $this->_lastmessage]);

        $this->dispatchEventRemote();
    }

    /**
     * Dispatches the incoming event to a callback
     *
     * Dispatches a model wich was created from a gateway event to a remote callback.
     * This is done by the Nebucord_EventTabel class wich holds the callbacks and events on where the event should
     * be dispatched.
     */
    public function dispatchEventRemote() {
        $evt_ar = $this->_evttbl->getEvents($this->_lastopcode);
        for($i = 0; $i < count($evt_ar); $i++) {
            if($this->_lastevent === null) {
                call_user_func(array($evt_ar[$i]['class'], $evt_ar[$i]['method']), $this->_model);
            } else {
                if(isset($evt_ar[$this->_lastevent][$i])) {
                    call_user_func(array($evt_ar[$this->_lastevent][$i]['class'], $evt_ar[$this->_lastevent][$i]['method']), $this->_model);
                }
            }
        }
    }

    /**
     * Dispatches the incoming event to the local controller.
     *
     * Some events need to be processed locally. This method dispatches the last event to the method invoker wich in
     * turn can react to the event-model wich is returned.
     *
     * @return Nebucord_Model
     */
    public function dispatchEventLocal() {
    	return $this->_model;
    }
}
