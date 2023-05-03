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

use Connfetti\INI\Config\Config;

/**
 * Class Configloader
 *
 * It utilizes the Connfetti-INI library wich parses an INI file and
 * stores it contents in a stdClass.
 * More about the Connfetti-INI lib here: https://github.com/Eurobertics/Connfetti-INI
 *
 * @package Connfetti\INI\Config\Config
 * @package Nebucord\Base
 */
class Configloader {

    /** @var Config $cfg Connfetti-INI config object */
    private $cfg;

    /**
     * Configloader constructor.
     *
     * Sets up the Config object.
     */
    public function __construct(string $configfile = 'nebucord.ini', string $configpath = './') {
        $this->cfg = new Config($configfile, $configpath);
    }

    /**
     * Configloader destructor.
     *
     * Nullifies the Config object.
     */
    public function __destruct() {
        $this->cfg = null;
    }

    /**
     * Returns the user ACL.
     *
     * Returns the user snowflakes which can control the bot.
     *
     * @return array An one dimensional array of user snowflakes.
     */
    private function getControlUsers()
    {
        $acl = $this->cfg->acl;
        if(strpos($acl, ',') === false) {
            return [$acl];
        }
        $users_ex = explode(',', $this->cfg->acl);
        $returnacl = array();
        for($i = 0; $i < count($users_ex); $i++) {
            $returnacl[] = $users_ex[$i];
        }
        return $returnacl;
    }

    /**
     * Returns the intents bitmask.
     *
     * Returns the bitmask of the intents which needs to be observed.
     *
     * @return int The bitmask of the observed intents.
     */
    private function getIntentsBitmask()
    {
        $oIntents = $this->cfg->intents;
        $bitmask = 0;
        foreach($oIntents as $intent => $state) {
            if($state === 'true') {
                $bitmask += constant('Nebucord\Base\StatusList::INTENT_' . $intent);
            }
        }
        return $bitmask;
    }

    /**
     * Build and gets the bot config parameters.
     *
     * Builds the bot config parameters from the .ini file and returns
     * it as an array for the $_params propertie of Nebucord class.
     *
     * @return array The config parameter as an key=>value array.
     */
    public function returnParams()
    {
        $params['token'] = $this->cfg->bottoken;
        $params['ctrlusr'] = $this->getControlUsers();
        $params['wsretries'] = $this->cfg->websocket->retries;
        $params['dmonfailures'] = $this->cfg->websocket->dmonfailures;
        $params['intents'] = $this->getIntentsBitmask();
        return $params;
    }
}
