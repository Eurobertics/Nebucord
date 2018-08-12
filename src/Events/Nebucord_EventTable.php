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

namespace Nebucord\Events;

use Nebucord\Base\Nebucord_EventTable_Base_Abstract;

/**
 * Class Nebucord_EventTable
 *
 * This class represents a table of possible callbacks from the Discord websocket gateway. It is used
 * to map self created callback functions to a OP code.
 *
 * @package Nebucord\Events
 */
class Nebucord_EventTable extends Nebucord_EventTable_Base_Abstract {

    /** @var object $_instance This var holds the singleton for the event table. */
    private static $_instance;

    /** @var array $_evttbl This array holds the list of given callbacks, it represents the callback stack. */
    private $_evttbl;

    /**
     * Creates an EventTabel instance.
     *
     * The event table is a singleton, to avoid mixing interactions with event tables, only one instance may
     * exists.
     *
     * @return Nebucord_EventTable|object
     */
    public static function create() {
        if(self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    /**
     * Deletes an EventTable instance.
     *
     * Sometimes it may usefull to delete an existing instance. This method does what it says.
     *
     * @param Nebucord_EventTable|object $instance The instance to delete.
     */
    public static function delete($instance) {
        if($instance instanceof Nebucord_EventTable) {
            unset($instance);
        }
    }

    /**
     * Nebucord_EventTable constructor.
     *
     * Starts the event table by resetting possible existent event stacks.
     */
    protected function __construct() {
        parent::__construct();
        $this->_evttbl = array();
    }

    /**
     * Nebucord_EventTable destructor.
     *
     * Cleans up the event table and removes existent event stacks.
     */
    public function __destruct() {
        parent::__destruct();
        $this->_evttbl = array();
    }

    /**
     * Adds an event callback.
     *
     * Abstract declaration.
     *
     * @see Nebucord_EventTable_Base_Abstract::addEvent()
     *
     * @param mixed $class EventTabel class.
     * @param string $method Method of the class to be called.
     * @param string $gwevt Gatewayevent to catch.
     * @param integer $opcode OP code from the gateway to catch.
     */
    public function addEvent($class, $method, $gwevt = null, $opcode = 0) {
        if(!is_int($opcode) || $opcode < 0 || $opcode > 11) {
            $opcode = 0;
        }
        $evt_ar = array("class" => $class, "method" => $method);
        if($gwevt === null) {
            $this->_evttbl[$opcode][] = $evt_ar;
        } else {
            $this->_evttbl[$opcode][$gwevt][] = $evt_ar;
        }
        unset($evt_ar);
    }

    /**
     * Gets an array of stored events.
     *
     * Abstract delcaration.
     *
     * @see Nebucord_EventTable_Base_Abstract::getEvents()
     *
     * @param integer $opcode OP code for selected stack.
     * @return array Stack of events from the given OP code.
     */
    public function getEvents($opcode) {
        if(!is_int($opcode) || $opcode < 0 || $opcode > 11) {
            $opcode = 0;
        }
        if(!isset($this->_evttbl[$opcode])) {
            return array();
        }
        return $this->_evttbl[$opcode];
    }
}
