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

use Nebucord\REST\Base\Nebucord_RESTHTTPClient;

/**
 * Class NebucordREST (REST part)
 *
 * while the websocket only sends events except some basic sendings (mostly for connection purposes), this part
 * of the API is designed to send request to Discord. This is done by a REST based API.
 */
class NebucordREST {

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
     * Creates action class for requests.
     *
     * Returns an action class based on the requested action name. This is a basic wrapper to get the actions if
     * available.
     * If a action ressource is not available, null is returned.
     *
     * For more information about action see: @see https://discordapp.com/developers/docs/intro -> resources
     *
     * @param string $name The action name to be created for requests.
     * @return object|null The action resource of null if not available.
     */
    public function __get($name) {
    	$actionclass = "Nebucord\REST\Action\Nebucord_REST".ucfirst($name);
    	if(class_exists($actionclass)) {
	    	$httpclient = new Nebucord_RESTHTTPClient($this->_params);
			$httpclient->setupBaseHeader();
		    return new $actionclass($httpclient);
    	}
    	return null;
    }
}
