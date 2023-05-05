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

use Nebucord\Interfaces\iActionTable;
use Nebucord\Base\StatusList;
use Nebucord\Factories\ModelFactory;
use Nebucord\Models\Model;
use Nebucord\NebucordREST;
use Nebucord\REST\Base\RestStatusList;

/**
 * Class ActionTable
 *
 * This class can be overritten with the iActionTable interface. It represents the default actions
 * wich are needed to run Nebucord even without external EventTable for callback operations.
 *
 * @package Nebucord\Events
 */
class ActionTable implements iActionTable {

    /**
     * ActionTable constructor.
     *
     * Sets itself up.
     */
    public function __construct() {
    }

    /**
     * ActionTable destructor.
     *
     * Shutds itself down.
     */
    public function __destruct() {
    }

    /**
     * The shutdown method.
     *
     * @param string $command The command wich invokes this method.
     * @return \Nebucord\Models\Model The model returned on this method.
     *@see iActionTable::doShutdown()
     *
     */
    public function doShutdown($command) {
        $command = strtolower($command);
        if($command == self::SHUTDOWN) {
            $oStatusUpdateModel = ModelFactory::create(StatusList::OP_STATUS_UPDATE);
            $oStatusUpdateModel->populate(
                [
                    'op' => StatusList::OP_STATUS_UPDATE,
                    'd' => [
                        'since' => time() * 1000,
                        'game' => null,
                        'status' => 'offline',
                        'afk' => false
                    ]
                ]
            );
            return $oStatusUpdateModel;
        }
        return null;
    }

    /**
     * The setStatus method.
     *
     * @param string $command The command wich invokes this method.
     * @return \Nebucord\Models\Model The model returned on this method.
     *@see iActionTable::setStatus()
     *
     */
    public function setStatus($command) {
        $command = strtolower($command);

        $status_ar = array("online", "invisible", "dnd", "idle");
        $activity_ar = array("game" => 0, "streaming" => 1, "listening" => 2);

        if(substr($command, 0, strpos($command, " ")) == self::SETSTATUS) {
            $oStatusUpdateModel = ModelFactory::create(StatusList::OP_STATUS_UPDATE);
            $oStatusUpdateModel->since = (time() * 1000);
            $oStatusUpdateModel->activities = [];
            $oStatusUpdateModel->afk = false;

            if(strpos($command, "#") !== false) {
                $activity = substr($command, strpos($command, "#") + 1, -1);
                $command = str_replace("#".$activity."#", "", $command);
                $activity_cmdar = explode("|", $activity);
                $activity_setar = array("name" => $activity_cmdar[0], "type" => $activity_ar[$activity_cmdar[1]]);
                if($activity_ar[$activity_cmdar[1]] == 1 && isset($activity_cmdar[2])) {
                    $activity_setar['url'] = $activity_cmdar[2];
                }
                $activityar[] = $activity_setar;
                $oStatusUpdateModel->activities = $activityar;
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
     * @param string $command The command wich invokes this method.
     * @return \Nebucord\Interfaces\iModelREST The model returned on this method.
     *@see iActionTable::getHelp()
     *
     */
    public function getHelp($command) {
        if($command == self::GETHELP) {
            $message = array("title" => "Available Bot commands",
                "description" => "
                The following commands are currently available\n*needs the bot snowflake after command
                (!command botid parameters)*:
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
            $oMessageCreate = ModelFactory::createREST(RestStatusList::REST_CREATE_MESSAGE);
            $oMessageCreate->populate(['content' => null, 'embed' => $message]);
            return $oMessageCreate;
        }
        return null;
    }

    /**
     * The doEcho method.
     *
     * @param string $command The command wich invokes this method.
     * @return \Nebucord\Interfaces\iModelREST The model returned on this method.
     *@see iActionTable::doEcho()
     *
     */
    public function doEcho($command) {
        if(substr($command, 0, strpos($command, " ")) == self::DOECHO) {
            $oMessageCreate = ModelFactory::createREST(RestStatusList::REST_CREATE_MESSAGE);
            $oMessageCreate->content = "Echo test:".substr($command, strpos($command, " "));
            return $oMessageCreate;
        }
        return null;
    }

    /**
     * The doSay method.
     *
     * @param string $command The command wich invokes this method.
     * @return \Nebucord\Interfaces\iModelREST The model returned on this method.
     *@see iActionTable::doSay()
     *
     */
    public function doSay($command) {
        if(substr($command, 0, strpos($command, " ")) == self::DOSAY) {
            $oMessageCreate = ModelFactory::createREST(RestStatusList::REST_CREATE_MESSAGE);
            $oMessageCreate->content = substr($command, strpos($command, " "));
            return $oMessageCreate;
        }
        return null;
    }

    /**
     * The doStatus method.
     *
     * @param string $command The command wich invokes this method.
     * @return \Nebucord\Interfaces\iModelREST The model returned on this method.
     *@see iActionTable::doStatus()
     *
     */
    public function doStatus($command) {
        if($command == self::DOSTATUS) {
            $oMessageCreate = ModelFactory::createREST(RestStatusList::REST_CREATE_MESSAGE);
            $oMessageCreate->content = "Bot is up and running.";
            return $oMessageCreate;
        }
        return null;
    }

    /**
     * The doVersion method.
     *
     * @param string $command The command wich invokes this method.
     * @return \Nebucord\Interfaces\iModelREST The model returned on this method.
     *@see iActionTable::doVersion()
     *
     */
    public function doVersion($command) {
        if($command == self::DOVERSION) {
            $message = array("title" => "Bot version",
                "description" => "The requested bot is running with:",
                "fields" => array(
                    array(
                        "name" => "API",
                        "value" => StatusList::CLIENTBROWSER,
                        "inline" => false
                    ),
                    array(
                        "name" => "Version",
                        "value" => StatusList::VERSION,
                        "inline" => false
                    ),
                    array(
                        "name" => "Client host",
                        "value" => StatusList::CLIENTHOST,
                        "inline" => false
                    ),
                    array(
                        "name" => "OS",
                        "value" => StatusList::getOS(),
                        "inline" => false
                    ),
                    array(
                        "name" => "Device",
                        "value" => StatusList::getDevice(),
                        "inline" => false
                    )
                )
            );
            $oMessageCreate = ModelFactory::createREST(RestStatusList::REST_CREATE_MESSAGE);
            $oMessageCreate->populate(['content' => null, 'embed' => $message]);
            return $oMessageCreate;
        }
        return null;
    }

    /**
     * Restarts Nebucord
     *
     * @param string $command The command on which this action should fire (default: !reboot).
     * @return Model|null The model return to the runtime controller to execute the action by the ActionController.
     *@see iActionTable::doRestart()
     *
     */
    public function doRestart($command)
    {
        $oMessageCreateModel = ModelFactory::createREST(RestStatusList::REST_CREATE_MESSAGE);
        $oMessageCreateModel->populate(['content' => "Reconnecting to the gateway!"]);
        return $oMessageCreateModel;
    }

    /**
     * Restarts Nebucord
     *
     * @param string $command The command on which this action should fire (default: !listappcmds).
     * @param integer $botuserid The bot user id which owns the app commands (application id).
     * @param string $bottoken The bot token to authenticate when receiving the app commands.
     * @param integer $guild_id The guild id for listing the guild app commands
     * (mostly the guild where the command originates from).
     * @return Model|null The model return to the runtime controller to execute the action by the ActionController.
     *@see iActionTable::doListAppCommands()
     *
     */
    public function doListAppCommands($command, $botuserid, $bottoken, $guild_id)
    {
        $oNebucordREST = new NebucordREST(['token' => $bottoken]);
        $cmdsglobal = $oNebucordREST->createRESTExecutor()->executeFromArray(
            RestStatusList::REST_GET_GLOBAL_APPLICATION_COMMANDS, [
            'application_id' => $botuserid
        ]);
        $cmdsguild = $oNebucordREST->createRESTExecutor()->executeFromArray(
            RestStatusList::REST_GET_GUILD_APPLICATION_COMMANDS, [
            'application_id' => $botuserid,
            'guild_id' => $guild_id
        ]);
        $cmdtypes = [
            StatusList::APPLICATION_TYPE_CHAT_INPUT => "Slash command",
            StatusList::APPLICATION_TYPE_USER => "User interaction",
            StatusList::APPLICATION_TYPE_MESSAGE => "Message interaction"
        ];
        $cmdarray = array();
        if(is_array($cmdsguild)) {
            for($i = 0; $i < count($cmdsguild); $i++) {
                $cmdline = array();
                $cmdline['name'] = $cmdsguild[$i]->name." (ID: ".$cmdsguild[$i]->id.")";
                $cmdline['value'] = "Type: Guild command\nInteraction style: ".$cmdtypes[$cmdsguild[$i]->type]."\n
                Description ```".$cmdtypes[$cmdsguild[$i]->type]."```";
                $cmdline['inline'] = false;
                $cmdarray[] = $cmdline;
            }
        }
        if(is_array($cmdsglobal)) {
            for($i = 0; $i < count($cmdsglobal); $i++) {
                $cmdline = array();
                $cmdline['name'] = $cmdsglobal[$i]->name." (ID: ".$cmdsglobal[$i]->id.")";
                $cmdline['value'] = "Type: Guild command\nInteraction style: ".$cmdsglobal[$cmdsglobal[$i]->type]."\n
                Description ```".$cmdsglobal[$cmdsglobal[$i]->type]."```";
                $cmdline['inline'] = false;
                $cmdarray[] = $cmdline;
            }
        }
        if(count($cmdarray) == 0) {
            $cmdline = array();
            $cmdline['name'] = "No commands found";
            $cmdline['value'] = "No global or guild commands found";
            $cmdline['inline'] = false;
            $cmdarray[] = $cmdline;
        }
        $message = array(
            "title" => "Available Bot commands",
            "description" => "Lists all application commands registered by this bot (application),
            this includes global commands, as well as guild commands",
            "fields" => $cmdarray
        );
        unset($oNebucordREST);
        $oMessageCreate = ModelFactory::createREST(RestStatusList::REST_CREATE_MESSAGE);
        $oMessageCreate->populate(['content' => null, 'embed' => $message]);
        return $oMessageCreate;
    }
}
