<?php
/**
 *
 * Nebucord - A Discord Websocket and REST API
 *
 * Copyright (C) 2021 Bernd Robertz
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

namespace Nebucord\REST\Base;

use Connfetti\IO\Reader\CSVReader;

/**
 * Class Nebucord_RESTAPIEndpointsLoader
 *
 * This class is responsible for loading the CSV file containing the API endpoints and request methods for the
 * REST gateway.
 *
 * For detailed REST information visit the Discord developer site: https://discordapp.com/developers/docs/intro
 *
 * @package Nebucord\REST\Base
 */
class Nebucord_RESTAPIEndpointsLoader
{
    /** @var CSVReader $fileloader The Confetti-IO file reader. */
    private $fileloader;

    /** @var array $restdata Holds the array with the possible REST endpoint data. */
    private $restdata = array();

    /**
     * Nebucord_RESTAPIEndpointsLoader constructor
     *
     * loads needed ressources and sets everything up for this class.
     */
    public function __construct()
    {
        $this->fileloader = new CSVReader('./src/REST/Base/restrequestlist.csv');
        $this->fileloader->load();
        $this->setupRestArray();
    }

    /**
     * Nebucord_RESTAPIEndpointsLoader destructor
     *
     * releases ressource after finishing everything.
     */
    public function __destruct()
    {
        $this->restdata = array();
    }

    private function setupRestArray()
    {
        $restarray = array();
        $csvdata = $this->fileloader->getContent();
        for($i = 0; $i < count($csvdata); $i++) {
            for($ii = 1; $ii < count($csvdata[$i]); $ii++) {
                $restarray[$csvdata[$i][0]][] = $csvdata[$i][$ii];
            }
        }
        $this->restdata = $restarray;
    }

    public function getRestArray()
    {
        return $this->restdata;
    }
}