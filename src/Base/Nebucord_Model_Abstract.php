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

use Nebucord\Models\Nebucord_Model_REST;
use Nebucord\REST\Base\Nebucord_RESTBuildAPIEndpoints;

/**
 * Class Nebucord_Model_Abstract
 *
 * Abstract class for Nebucorc models. This class implements the basics wich every model must have.
 *
 * @package Nebucord\Base
 */
abstract class Nebucord_Model_Abstract {

    /** @var Nebucord_Status $_op Holds the OP code for a model. */
    protected $_op = null;

    /** @var Nebucord_Status $_s The current sequence from the Discord gateway. */
    protected $_s = null;

    /** @var Nebucord_Status $_t The current gateway event. */
    protected $_t = null;

    /** @var array $_data Storage for all model data. */
    private $_data = array();

    /**
     * Nebucord_Model_Abstract constructor.
     *
     * Stores the basic data into the model (OP code and gw-event).
     *
     * @param Nebucord_Status $op The OP code for an event.
     * @param Nebucord_Status $event The gatewayevent.
     */
    public function __construct($op = null, $event = null) {
        $this->_op = $op;
        $this->_t = $event;
    }

    /**
     * Nebucord_Model_Abstract Destructor
     *
     * Cleans everything up.
     */
    public function __destruct() {
        $this->_data = array();
    }

    /**
     * Magic model getter.
     *
     * Every valid property of a model can be received with this method.
     *
     * @param string $name The name of the property.
     * @return mixed The value of the given property name, if valid.
     */
    public function __get($name) {
        if($name == 'op' || $name == 't' || $name == 's') {
            $targetprop = '_'.$name;
            return $this->$targetprop;
        }
        if(array_key_exists('_'.$name, $this->_data)) {
            return $this->_data['_'.$name];
        }
        return null;
    }

    /**
     * Magic model setter.
     *
     * Sets a value to valid property of a model.
     *
     * @param string $name The name of the property to write in.
     * @param mixed $value The value to be stored in the property name (if valid).
     */
    public function __set($name, $value) {
        if($name == 'op' || $name == 't' || $name == 's') {
            $targetprop = '_'.$name;
            $this->$targetprop = $value;
        } else {
            $this->_data['_' . $name] = $value;
        }
    }

    /**
     * Magic model property check
     *
     * Checks if a property exists.
     *
     * @param string $name The property name to check.
     * @return bool If the property exists true, otherwise false.
     */
    public function __isset($name) {
        if(array_key_exists('_'.$name, $this->_data)) { return true; }
        return false;
    }

    /**
     * Magic model property delete.
     *
     * Deletes a property if exits.
     *
     * @param string $name The name of the property to be deleted.
     */
    public function __unset($name) {
        unset($this->_data['_'.$name]);
    }

    /**
     * Fills a model by an array.
     *
     * The given array will we iterated and stored in the modely key => property, $value => property value.
     * If exists.
     *
     * @param array $data The data to ba stored within the model.
     */
    public function populate(array $data) {
        $this->_op = (!isset($data['op'])) ? null : $data['op'];
        $this->_s = (!isset($data['s'])) ? null : $data['s'];
        $this->_t = (!isset($data['t'])) ? null : $data['t'];
        if(isset($data['d'])) {
            foreach ($data['d'] as $property => $value) {
                $this->_data['_'.$property] = $value;
            }
        } else {
            foreach ($data as $property => $value) {
                $this->_data['_'.$property] = $value;
            }
        }
    }

    /**
     * Returns an array representation of a model.
     *
     * Every property wich exists in a model will be returned by "key=>value" pair in an array.
     * Methods are not exported. This is often needed to convert a model to array before preparing to send somewhere.
     *
     * @return array The array with the propertys=>values of the model.
     */
    public function toArray() {
        $retar = array();

        foreach($this->_data as $prop => $value) {
            if($this->_op != null) {
                $retar['op'] = $this->_op;
                if($this->_op == Nebucord_Status::OP_HEARTBEAT) {
                    $retar['d'] = $this->_d;
                } else {
                    $retar['d'][substr($prop, 1)] = $this->_data[$prop];
                }
            } else {
                //if($prop == "_channelid" || $prop == "_requesttype") { continue; }
                $retar[substr($prop, 1)] = $this->_data[$prop];
            }
        }

        return $retar;
    }
}
