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

use Nebucord\Base\Configloader;
use Nebucord\Base\StatusList;
use Nebucord\Http\WebSocketClient;
use Nebucord\Controller\RuntimeController;

/**
 * Class Nebucord (WebSocket part)
 *
 * The main class where it all starts. It initials all other needed classes, create controllers and starts all
 * the hard work wich the controller continue to do after starting.
 */
class Nebucord {

    /** @var null $_config Configuration parameter. */
    private $_config = null;

    /** @var WebSocketClient $_wscon The websocket object for transfering data. */
    private $_wscon = null;

    /** @var RuntimeController $_runtimecontroller The craeted runtime controller wich does the hard work. */
    private $_runtimecontroller = null;

    /** @var \Nebucord\Events\EventTable $_evttbl An event table object given by external or created internal. */
    private $_evttbl = null;

    /** @var \Nebucord\Events\ActionTable $_acttbl An action table object given by external or created interanal. */
    private $_acttbl = null;

    /** @var array $_params User given params by the constructor. */
    private $_params = array();

    /** @var bool $_bootstrappingdone Indicates if bootstrap is properly done. */
    private $_bootstrappingdone = false;

    /**
     * Nebucord constructor.
     *
     * Starts everything and setup the user given parameters.
     *
     * @param array $params User given parameter such as controll user or bot token.
     */
    public function __construct(array $params = array()) {
        Logging\MainLogger::infoImportant(
            "Starting Nebucord v. ".Base\StatusList::VERSION." on ".Base\StatusList::CLIENTHOST
        );
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
        $this->_acttbl = null;
        $this->_params = array();
        Logging\MainLogger::infoImportant("Nebucord successfuly exited.");
    }

    /**
     * Bootstrapping before start.
     *
     * Starts the basics and gets configuration parameters. After bootstrapping the socket
     * connection and configurations, Nebucord is able to run.
     *
     * @param string $configfile A INI config file for configuration.
     * @param string $configpath The path for the config file.
     * @return Nebucord Returns itself (Nebucord).
     */
    public function bootstrap(string $configfile = 'nebucord.ini', string $configpath = './') {
        if(count($this->_params) == 0) {
            $this->_config = new Configloader($configfile, $configpath);
            $this->_params = $this->_config->returnParams();
        }
        if(empty($this->_params['intents'])) {
            $this->_params['intents'] = StatusList::INTENTS_DEFAULT_BITMASK;
        }
        if(empty($this->_params['wsretries'])) {
            $this->_params['wsretries'] = StatusList::MAX_RECONNECT_TRIES;
        }
        if(empty($this->_params['dmonfailures'])) {
            $this->_params['dmonfailures'] = StatusList::DMONFAILURES_DEFAULT;
        }
        $this->_wscon = WebSocketClient::getInstance();
        $this->_bootstrappingdone = true;
        return $this;
    }

    /**
     * Sets user params.
     *
     * If not given by constructor, the user can throw in the parameters here as well.
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
     * @param Events\EventTable $eventtable The event table object.
     * @return Nebucord Returns itself (Nebucord).
     */
    public function setEventTable(Events\EventTable $eventtable) {
        $this->_evttbl = $eventtable;
        Logging\MainLogger::info("Eventtable set...");
        return $this;
    }

    /**
     * Sets action table.
     *
     * As for an event table, external user are able to provide actions on internal events if needed, this method
     * sets such an action table object.
     *
     * @param Events\ActionTable $actiontable The action table object.
     * @return Nebucord Returns itself (Nebucord).
     */
    public function setActionTable($actiontable) {
        if($actiontable instanceof Interfaces\IActionTable) {
            $this->_acttbl = $actiontable;
        }
        Logging\MainLogger::info("Actiontable set...");
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
        if(!$this->_bootstrappingdone) {
            Logging\MainLogger::error(
                "Bootstrapping not done, aborting! (did you forget to call Nebucord::bootstrap()?)"
            );
            return;
        }
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
            $this->_evttbl = Events\EventTable::create();
            Logging\MainLogger::info("No eventable set, using default one...");
        }
        if($this->_acttbl == null) {
            $this->_acttbl = new Events\ActionTable();
            Logging\MainLogger::info("No actiontable set, using default one...");
        }
        $this->_runtimecontroller = new RuntimeController(
            $this->_wscon,
            $this->_evttbl,
            $this->_acttbl,
            $this->_params
        );
    }

    /**
     * Clean up the main loop
     *
     * After the conditions are set to exit, before leaving the process, here are some basic clean ups.
     */
    private function cleanup() {
        WebSocketClient::destroyInstance($this->_wscon);
        Events\EventTable::delete($this->_evttbl);
        unset($this->_runtimecontroller);
        Logging\MainLogger::info("Everything cleaned up...");
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
     * @return Base\StatusList The current runtime state.
     */
    public function getRuntimeState() {
        return $this->_runtimecontroller->getRuntimeState();
    }
}
