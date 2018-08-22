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

use Nebucord\Interfaces\Nebucord_IActionTable;
use Nebucord\Base\Nebucord_Status;
use Nebucord\Factories\Nebucord_Model_Factory;

/**
 * Class Nebucord_ActionTable
 *
 * This class can be overritten with the Nebucord_IActionTable interface. It represents the default actions
 * wich are needed to run Nebucord even without external EventTable for callback operations.
 *
 * @package Nebucord\Events
 */
class Nebucord_ActionTable implements Nebucord_IActionTable {

    /**
     * Nebucord_ActionTable constructor.
     *
     * Sets itself up.
     */
    public function __construct() {
    }

    /**
     * Nebucord_ActionTable destructor.
     *
     * Shutds itself down.
     */
    public function __destruct() {
    }

    /**
     * The shutdown method.
     *
     * @see Nebucord_IActionTable::doShutdown()
     *
     * @param string $command The command wich invokes this method.
     * @return \Nebucord\Models\Nebucord_Model The model returned on this method.
     */
    public function doShutdown($command) {
        $command = strtolower($command);
        if($command == self::SHUTDOWN) {
            $oStatusUpdateModel = Nebucord_Model_Factory::create(Nebucord_Status::OP_STATUS_UPDATE);
            $oStatusUpdateModel->populate(['op' => Nebucord_Status::OP_STATUS_UPDATE, 'd' => ['since' => time() * 1000, 'game' => null, 'status' => 'offline', 'afk' => false]]);
            return $oStatusUpdateModel;
        }
        return null;
    }

    /**
     * The setStatus method.
     *
     * @see Nebucord_IActionTable::setStatus()
     *
     * @param string $command The command wich invokes this method.
     * @return \Nebucord\Models\Nebucord_Model The model returned on this method.
     */
    public function setStatus($command) {
        $command = strtolower($command);

        $status_ar = array("online", "invisible", "dnd", "idle");
        $activity_ar = array("game" => 0, "streaming" => 1, "listening" => 2);

        if(substr($command, 0, strpos($command, " ")) == self::SETSTATUS) {
            $oStatusUpdateModel = Nebucord_Model_Factory::create(Nebucord_Status::OP_STATUS_UPDATE);
            $oStatusUpdateModel->since = (time() * 1000);
            $oStatusUpdateModel->game = null;
            $oStatusUpdateModel->afk = false;

            if(strpos($command, "#") !== false) {
                $activity = substr($command, strpos($command, "#") + 1, strpos($command, "#") - strpos($command, "#") - 1);
                $command = str_replace("#".$activity."#", "", $command);
                $activity_cmdar = explode("|", $activity);
                $activity_setar = array("name" => $activity_cmdar[0], "type" => $activity_ar[$activity_cmdar[1]], "url" => $activity_cmdar[2]);
                $oStatusUpdateModel->game = $activity_setar;
            }

            $commandline_ar = explode(" ", $command);
            for($i = 0; $i < count($commandline_ar); $i++) {
                for($ii = 0; $ii < count($status_ar); $ii++) {
                    if($commandline_ar[$i] == $status_ar[$ii]) {
                        $oStatusUpdateModel->status = $status_ar[$ii];
                    }
                }
                if($commandline_ar[$i] == "true") {
                    $oStatusUpdateModel->afk = true;
                }
            }
            return $oStatusUpdateModel;
        }
        return null;
    }

    /**
     * The getHelp method.
     *
     * @see Nebucord_IActionTable::getHelp()
     *
     * @param string $command The command wich invokes this method.
     * @return \Nebucord\Interfaces\Nebucord_IModelREST The model returned on this method.
     */
    public function getHelp($command) {
        if($command == self::GETHELP) {
            $message = array("title" => "Available Bot commands",
                "description" => "The following commands are currently available\n*needs the bot snowflake after command (!command botid parameters)*:
    ",
                "fields" => array(
                    array(
                        "name" => "!shutdown",
                        "value" => "Shutdown bot and exits the websocket connection.",
                        "inline" => false
                    ),
                    array(
                        "name" => "!setstatus",
                        "value" => "Sets status for the bot, i. E.:\n
            ```!setstatus status [afk activity]```
            ```Status: online|invisible|dnd|idle\nAFK: true|false\nActivity: #name|activitytype|http://exampleurl>#```
            ```Activitytype: game|streaming|listening```
            ",
                        "inline" => false
                    ),
                    array(
                        "name" => "!commands",
                        "value" => "List of possible commands (this list).",
                        "inline" => false
                    ),
                    array(
                        "name" => "!echottest",
                        "value" => "Echos message for testing:\n
            ```!setstatus message```",
                        "inline" => false
                    ),
                    array(
                        "name" => "!say",
                        "value" => "Repeats what a user say:\n
            ```!say message```",
                        "inline" => false
                    ),
                    array(
                        "name" => "!version",
                        "value" => "Detailed bot and machine status:\n
            ```!version```",
                        "inline" => false
                    )
                )
            );
            $oMessageCreate = Nebucord_Model_Factory::createREST(Nebucord_Status::REQ_CREATE_MESSAGE);
            $oMessageCreate->populate(['content' => null, 'embed' => $message]);
            return $oMessageCreate;
        }
        return null;
    }

    /**
     * The doEcho method.
     *
     * @see Nebucord_IActionTable::doEcho()
     *
     * @param string $command The command wich invokes this method.
     * @return \Nebucord\Interfaces\Nebucord_IModelREST The model returned on this method.
     */
    public function doEcho($command) {
        if(substr($command, 0, strpos($command, " ")) == self::DOECHO) {
            $oMessageCreate = Nebucord_Model_Factory::createREST(Nebucord_Status::REQ_CREATE_MESSAGE);
            $oMessageCreate->content = "Echo test:".substr($command, strpos($command, " "));
            return $oMessageCreate;
        }
        return null;
    }

    /**
     * The doSay method.
     *
     * @see Nebucord_IActionTable::doSay()
     *
     * @param string $command The command wich invokes this method.
     * @return \Nebucord\Interfaces\Nebucord_IModelREST The model returned on this method.
     */
    public function doSay($command) {
        if(substr($command, 0, strpos($command, " ")) == self::DOSAY) {
            $oMessageCreate = Nebucord_Model_Factory::createREST(Nebucord_Status::REQ_CREATE_MESSAGE);
            $oMessageCreate->content = substr($command, strpos($command, " "));
            return $oMessageCreate;
        }
        return null;
    }

    /**
     * The doStatus method.
     *
     * @see Nebucord_IActionTable::doStatus()
     *
     * @param string $command The command wich invokes this method.
     * @return \Nebucord\Interfaces\Nebucord_IModelREST The model returned on this method.
     */
    public function doStatus($command) {
        if($command == self::DOSTATUS) {
            $oMessageCreate = Nebucord_Model_Factory::createREST(Nebucord_Status::REQ_CREATE_MESSAGE);
            $oMessageCreate->content = "Bot is up and running.";
            return $oMessageCreate;
        }
        return null;
    }

    /**
     * The doVersion method.
     *
     * @see Nebucord_IActionTable::doVersion()
     *
     * @param string $command The command wich invokes this method.
     * @return \Nebucord\Interfaces\Nebucord_IModelREST The model returned on this method.
     */
    public function doVersion($command) {
        if($command == self::DOVERSION) {
            $message = array("title" => "Bot version",
                "description" => "The requested bot is running with:",
                "fields" => array(
                    array(
                        "name" => "API",
                        "value" => Nebucord_Status::CLIENTBROWSER,
                        "inline" => false
                    ),
                    array(
                        "name" => "Version",
                        "value" => Nebucord_Status::VERSION,
                        "inline" => false
                    ),
                    array(
                        "name" => "Client host",
                        "value" => Nebucord_Status::CLIENTHOST,
                        "inline" => false
                    ),
                    array(
                        "name" => "OS",
                        "value" => Nebucord_Status::getOS(),
                        "inline" => false
                    ),
                    array(
                        "name" => "Device",
                        "value" => Nebucord_Status::getDevice(),
                        "inline" => false
                    )
                )
            );
            $oMessageCreate = Nebucord_Model_Factory::createREST(Nebucord_Status::REQ_CREATE_MESSAGE);
            $oMessageCreate->populate(['content' => null, 'embed' => $message]);
            return $oMessageCreate;
        }
        return null;
    }
}
