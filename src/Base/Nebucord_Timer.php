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

/**
 * Class Nebucord_Timer
 *
 * Timer class to measure time in millisecons. This is used to calculate time or timeouts for events.
 *
 * @package Nebucord\Base
 */
class Nebucord_Timer {

    /** @var array $_timers Holds all active timers. */
    private $_timers = array();

    /** @var int $_time_modifier Sets the timemodifier (1x = seconds, 1000x = milliseconds i. E.). This is class global.*/
    private $_time_modifier = 1;

    /**
     * Nebucord_Timer constructor.
     *
     * Creates ressources for the timer and resets old timers.
     *
     * @param int $timemodifier The time modifier to measure other time steps as seconds.
     */
    public function __construct($timemodifier = 1) {
        $this->_time_modifier = ($timemodifier > 0) ? $timemodifier : 1;
        $this->_timers = array();
    }

    /**
     * Nebucord_Timer destructor.
     *
     * Cleans up the timer array on end.
     */
    public function __destruct() {
        $this->_timers = array();
    }

    /**
     * Returns microtime as float.
     *
     * Returns time from the microtime() function as float in conjunction with the time modifier.
     *
     * @return float|int Returns current time as float/int.
     */
    private function mtAsFloat() {
        list($usec, $sec) = explode(" ", microtime());
        return (((float)$usec + (float) + $sec) * $this->_time_modifier);
    }

    /**
     * Returns the time modifer
     *
     * This method returns the given time modifier.
     *
     * @return int The time modifier.
     */
    public function getTimeModifier() {
        return $this->_time_modifier;
    }

    /**
     * Gets time count.
     *
     * Returns the time since the timer was started/restarted.
     *
     * @param int $id The timer id.
     * @return int The time past since start/restart.
     */
    public function getTime($id = 0) {
        if(!$this->_timers[$id]) { return 0; }
        $this->_timers[$id]['time'] = $this->mtAsFloat() - $this->_timers[$id]['start'];
        return $this->_timers[$id]['time'];
    }

    /**
     * Starts a timer
     *
     * Starts a timer and adds it to the local timer array.
     *
     * @param int $id The timer id.
     * @return int 0 on failure, 1 on success.
     */
    public function startTimer($id = 0) {
        if($id < 0) { return 0; }
        $this->_timers[$id]['start'] = $this->mtAsFloat();
        $this->_timers[$id]['time'] = 0;
        return 1;
    }

    /**
     * Restarts a timer
     *
     * Restarts a timer and update it in the local timer array.
     *
     * @param int $id The timer id.
     * @return int 0 on failure, 1 on success.
     */
    public function reStartTimer($id = 0) {
        if($id < 0) { return 0; }
        $this->_timers[$id]['start'] = $this->mtAsFloat();
        $this->_timers[$id]['time'] = 0;
        return 1;
    }
}
