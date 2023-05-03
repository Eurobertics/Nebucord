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
use Nebucord\REST\Action\Executor;
use Nebucord\REST\Base\HttpClient;

/**
 * Class NebucordREST (REST part)
 *
 * while the websocket only sends events except some basic sendings (mostly for connection purposes), this part
 * of the API is designed to send request to Discord. This is done by a REST based API.
 */
class NebucordREST {

    /** @var null $_config Configuration parameter. */
    private $_config = null;

    /** @var array User given parameters.- */
    private $_params;

    /**
     * NebucordREST constructor.
     *
     * Starts up the REST client and sets user parameters.
     *
     * @param array $params The user given params such as bot token.
     */
    public function __construct(array $params = array()) {
        $this->_params = $params;
        if(empty($params)) {
            $this->bootstrap();
        }
    }

    /**
     * NebucordREST destructor.
     *
     * After REST call cleans everything up.
     */
    public function __destruct() {
        $this->_params = array();
    }

    /**
     * Bootstrapping before start.
     *
     * Starts the basics and gets configuration parameters. After bootstrapping the REST
     * executor should be started to create request.
     *
     * @param string $configfile A INI config file for configuration.
     * @param string $configpath The path for the config file.
     * @return NebucordREST Returns itself (NebucordREST).
     */
    public function bootstrap(string $configfile = 'nebucord.ini', string $configpath = './') {
        if(count($this->_params) == 0) {
            $this->_config = new Configloader($configfile, $configpath);
            $this->_params = $this->_config->returnParams();
        }
        return $this;
    }

    /**
     * Creates the REST executor
     *
     * The REST executor is a class which provides all ressources to perform a
     * REST request to the Discord REST gateway.
     * This includes also the base preparing of the http client for REST.
     *
     * @return Executor The REST executor for performing the REST request.
     */
    public function createRESTExecutor()
    {
        $httpclient = new HttpClient($this->_params);
        $httpclient->setupBaseHeader();
        return new Executor($httpclient);
    }
}
