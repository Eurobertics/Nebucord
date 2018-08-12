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

namespace Nebucord;

use Nebucord\Base\Nebucord_Configloader;
use Nebucord\Http\Nebucord_WebSocket;
use Nebucord\Controller\Nebucord_RuntimeController;

/**
 * Class Nebucord (WebSocket part)
 *
 * The main class where it all starts. It initials all other needed classes, create controllers and starts all
 * the hard work wich the controller continue to do after starting.
 */
class Nebucord {

    /** @var null $_config Configuration parameter, currently not used. */
    private $_config = null;

    /** @var Nebucord_WebSocket $_wscon The websocket object for transfering data. */
    private $_wscon = null;

    /** @var Nebucord_RuntimeController $_runtimecontroller The craeted runtime controller wich does the hard work. */
    private $_runtimecontroller = null;

    /** @var \Nebucord\Events\Nebucord_EventTable $_evttbl An event table object given by external or created internal. */
    private $_evttbl = null;

    /** @var \Nebucord\Events\Nebucord_ActionTable $_acttbl An action table object given by external or created interanal. */
    private $_acttbl = null;

    /** @var array $_params User given params by the constructor. */
    private $_params = array();

    /**
     * Nebucord constructor.
     *
     * Starts everything and setup the user given parameters.
     *
     * @param array $params User given parameter such as controll user or bot token.
     */
    public function __construct(array $params = array()) {
        Logging\Nebucord_Logger::infoImportant("Starting Nebucord v. ".Base\Nebucord_Status::VERSION." on ".Base\Nebucord_Status::CLIENTHOST, "nebucord.log");
        $this->_evttbl = null;
        $this->_evttbl = null;
        $this->_params = $params;
    }

    /**
     * Nebucord destructor.
     *
     * After exiting this one cleans everything up.
     */
    public function __destruct() {
        $this->_config = null;
        $this->_wscon = null;
        $this->_runtimecontroller = null;
        $this->_evttbl = null;
        $this->_evttbl = null;
        $this->_params = array();
        Logging\Nebucord_Logger::infoImportant("Nebucord successfuly exited.", "nebucord.log");
    }

    /**
     * Bootstrapping before start.
     *
     * Starts the basics and gets configuration parameters (currently not used). After bootstrapping the socket
     * connection and configurations, Nebucord is able to run.
     *
     * @return Nebucord Returns itself (Nebucord).
     */
    public function bootstrap() {
        $this->_config = new Nebucord_Configloader();
        $this->_wscon = Nebucord_WebSocket::getInstance();
        return $this;
    }

    /**
     * Sets user params.
     *
     * If not given by constructor, the externals user can throw in the parameters here as well.
     *
     * @param array $params The user given parameters such as constroll user or bot token.
     * @return Nebucord Returns itself (Nebucord).
     */
    public function setParams(array $params = array()) {
        $this->_params = $params;
        return $this;
    }

    /**
     * Sets event table.
     *
     * As designed for external callbacks callings to get event from the gateway, this method accepts an
     * event table with the stacked used callbacks to be fired on events.
     *
     * @param Events\Nebucord_EventTable $eventtable The event table object.
     * @return Nebucord Returns itself (Nebucord).
     */
    public function setEventTable(Events\Nebucord_EventTable $eventtable) {
        $this->_evttbl = $eventtable;
        Logging\Nebucord_Logger::info("Eventtable set...", "nebucord.log");
        return $this;
    }

    /**
     * Sets action table.
     *
     * As for an event table, external user are able to provide actions on internal events if needed, this method
     * sets such an action table object.
     *
     * @param Events\Nebucord_ActionTable $actiontable The action table object.
     * @return Nebucord Returns itself (Nebucord).
     */
    public function setActionTable($actiontable) {
        if($actiontable instanceof Interfaces\Nebucord_IActionTable) {
            $this->_acttbl = $actiontable;
        }
        Logging\Nebucord_Logger::info("Actiontable set...", "nebucord.log");
        return $this;
    }

    /**
     * Fires Nebucord up.
     *
     * After everything is set up, this method initials the main loop and and starts the runtime controller.
     * Before starting, the final prepare step is done and on setting exit to the run state the cleanup method
     * is also called.
     */
    public function run() {
        $this->prepare();
        $this->_runtimecontroller->start();
        $this->cleanup();
    }

    /**
     * Prepares the main loop.
     *
     * Final initialisations step before entering main loop.
     */
    private function prepare() {
        if($this->_evttbl == null) {
            $this->_evttbl = Events\Nebucord_EventTable::create();
            Logging\Nebucord_Logger::info("No eventable set, using default one...", "nebucord.log");
        }
        if($this->_acttbl == null) {
            $this->_acttbl = new Events\Nebucord_ActionTable();
            Logging\Nebucord_Logger::info("No actiontable set, using default one...", "nebucord.log");
        }
        $this->_runtimecontroller = new Nebucord_RuntimeController($this->_wscon, $this->_evttbl, $this->_acttbl, $this->_params);
    }

    /**
     * Clean up the main loop
     *
     * After the conditions are set to exit, before leaving the process, here are some basic clean ups.
     */
    private function cleanup() {
        Nebucord_WebSocket::destroyInstance($this->_wscon);
        Events\Nebucord_EventTable::delete($this->_evttbl);
        unset($this->_runtimecontroller);
        Logging\Nebucord_Logger::info("Everything cleaned up...", "nebucord.log");
    }

    /**
     * Sets Nebucord runtime state.
     *
     * Actualize the runtime state through runtime controller.
     *
     * @param integer $state The new runtime state.
     */
    public function setRuntimeState($state) {
        $this->_runtimecontroller->setRuntimeState($state);
    }

    /**
     * Gets Nebucord runtime state.
     *
     * Returns the current runtimestate used by the runtime controller.
     *
     * @return Base\Nebucord_Status The current runtime state.
     */
    public function getRuntimeState() {
        return $this->_runtimecontroller->getRuntimeState();
    }
}
