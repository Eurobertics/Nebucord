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

namespace Nebucord\Base;

use Nebucord\Events\EventTable;

/**
 * Class AbstractEventTable
 *
 * This is the abstract base class for an event table, wich holds the callbacks to be fired on
 * various gateway events.
 *
 * @package Nebucord\Base
 */
abstract class AbstractEventTable {

    /**
     * AbstractEventTable constructor.
     *
     * Constructor for the event table.
     */
    protected function __construct() {
    }

    /**
     * AbstractEventTable constructor.
     *
     * Destructor for the event table.
     */
    public function __destruct() {
    }

    /**
     * Adds an event callback.
     *
     * This method is intended to add a self made method to the callback stack.
     * It requests the class and the methods to call, as well as the gatewayevent and the
     * OP code on wich the method should fire.
     *
     * Callbacks are stored on stacks separeted by OP code.
     *
     * @param mixed $class The class of the method wich should be executed.
     * @param string $method The method of the given class wich should be fired.
     * @param object StatusList $gwevent The gatway event on wich the callback will be executed (as of Nebucord::<GWEVT_*>).
     * @param integer StatusList $opcode The opcode from the gateway on wich the callback will be executed (as of Nebucord::<OP_*>).
     * @return null
     */
    abstract public function addEvent($class, $method, $gwevent, $opcode);

    /**
     * Gets an array of stored events.
     *
     * Abstract delcaration.
     *
     * @see EventTable::getEvents()
     */
    /**
     * Returns an array of stored callbacks.
     *
     * The stored callbacks wich should be fire on an gatewayevent can be returned with this method.
     * Used withing the controllers to determine wich callback should be fired based on the OP code.
     *
     * Callbacks are stored on stacks separeted by OP code.
     *
     * @param object StatusList|integer $opcode The opcode to determine the callback stack.
     * @return array|mixed The callback array stored on the give OP stack.
     */
    abstract public function getEvents($opcode);
}