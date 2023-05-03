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

namespace Nebucord\Interfaces;

use Nebucord\Models\Model;

/**
 * Interface iActionTable
 *
 * This sets the needed methods to be implemented by an ActionTable class if it needs to be overwritten.
 *
 * @package Nebucord\Interfaces
 */
interface iActionTable {
    /** @const string The const for shutting down Nebucord. */
    const SHUTDOWN = "!shutdown";

    /** @const string The const for setting a bot status. */
    const SETSTATUS = "!setstatus";

    /** @const string The const for getting commands help. */
    const GETHELP = "!commands";

    /** @const string The const for sending an test echo command. */
    const DOECHO = "!echotest";

    /** @const string The const for repeating a message by a bot. */
    const DOSAY = "!say";

    /** @var string The const for requesting status. */
    const DOSTATUS = "!status";

    /** @var string This is a public command, it shows the bot status. */
    const DOVERSION = "!version";

    /** @var string The bot will restart and reconnect to the gateway by this command. */
    const DOREBOOT = "!reboot";

    /** @var string List all application or slash commands. */
    const DOLISTAPPCOMMANDS = "!listappcmds";

    /**
     * Stops Nebucord and sets runtime to exit.
     *
     * Can be overwritten. This does everything wich is needed to shut down the webclient properly.
     *
     * @param string $command The command on wich this action shoud fire (default: !shutdown).
     * @return Model|null The model returned to the runtime controller to execute the action by the ActionController.
     */
    public function doShutdown($command);

    /**
     * Sets the bot status
     *
     * Can be overwritten. This sets a new status for the Nebucord driven bot.
     *
     * @param string $command The command on wich this action shoud fire (default: !setstatus).
     * @return Model|null The model returned to the runtime controller to execute the action by the ActionController.
     */
    public function setStatus($command);

    /**
     * Shows the available commands.
     *
     * Sends a message to the channel where it received the command and returns a list of available internal commands.
     *
     * @param string $command The command on wich this action shoud fire (default: !commands).
     * @return Model|null The model returned to the runtime controller to execute the action by the ActionController.
     */
    public function getHelp($command);

    /**
     * Does a test echo.
     *
     * Sends an echo with the same text wich was entered on the command to the channel where it was posted.
     *
     * @param string $command The command on wich this action shoud fire (default: !echotest).
     * @return Model|null The model returned to the runtime controller to execute the action by the ActionController.
     */
    public function doEcho($command);

    /**
     * Repeats what a user send.
     *
     * Repeats exactly what a user typed.
     *
     * @param string $command The command on wich this action shoud fire (default: !say).
     * @return Model|null The model returned to the runtime controller to execute the action by the ActionController.
     */
    public function doSay($command);

    /**
     * Returns bot status.
     *
     * Returns simply if bot is available.
     *
     * @param string $command The command on wich this action shoud fire (default: !status).
     * @return Model|null The model returned to the runtime controller to execute the action by the ActionController.
     */
    public function doStatus($command);

    /**
     * Returns bot version.
     *
     * Returns the current running version of the API.
     * This command is public.
     *
     * @param string $command The command on wich this action shoud fire (default: !status).
     * @return Model|null The model returned to the runtime controller to execute the action by the ActionController.
     */
    public function doVersion($command);

    /**
     * Restarts Nebucord
     *
     * Restarts Nebucord and reconnects to the Websocket Gateway.
     *
     * @param string $command The command on which this action should fire (default: !reboot).
     * @return Model|null The model return to the runtime controller to execute the action by the ActionController.
     */
    public function doRestart($command);

    /**
     * List application commands
     *
     * Since Discord API endpoint version 8 (AFAIK) Discord supports application and slash commands which needs
     * to be registered. This command lists all commands within a guild and all global commands.
     *
     * @param string $command The command on which this action should fire (default: !listappcmds).
     * @param integer $botuserid The bot user id which owns the app commands (application id).
     * @param string $bottoken The bot token to authenticate when receiving the app commands.
     * @param integer $guild_id The guild id for listing the guild app commands (mostly the guild where the command originates from).
     * @return Model|null The model return to the runtime controller to execute the action by the ActionController.
     */
    public function doListAppCommands($command, $botuserid, $bottoken, $guild_id);
}
