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

namespace Nebucord\Logging;

use Nebucord\Base\StatusList;

/**
 * Class MainLogger
 *
 * Simple logger class with some fancy color settings for CLI. Supports log file size for log splitting as well.
 *
 * @package Nebucord\Log
 */
class MainLogger {

    /** @var string|null $_logfilename The logfilename for logging further than CLI only.*/
    private $_logfilename;

    /** @var null|resource $_fd The file discriptor for file logging. */
    private $_fd = null;

    const CLI_BLACK = "0;30";
    const CLI_DARKGRAY = "1;30";
    const CLI_BLUE = "0;34";
    const CLI_LIGHTBLUE = "1;34";
    const CLI_GREEN = "0;32";
    const CLI_LIGHTGREEN = "1;32";
    const CLI_CYAN = "0;36";
    const CLI_LIGHTCYAN = "1;36";
    const CLI_RED = "0;31";
    const CLI_LIGHTRED = "1;31";
    const CLI_PURPLE = "0;35";
    const CLI_LIGHTPURPLE ="1;35";
    const CLI_BROWN = "0;33";
    const CLI_YELLOW = "1;33";
    const CLI_LIGHTGRAY = "0;37";
    const CLI_WHITE = "1;37";

    const CLI_BG_BLACK = "40";
    const CLI_BG_RED = "41";
    const CLI_BG_GREEN = "42";
    const CLI_BG_YELLOW = "43";
    const CLI_BG_BLUE = "44";
    const CLI_BG_MAGENTA = "45";
    const CLI_BG_CYAN = "46";
    const CLI_BG_LIGHTGRAY = "47";

    const CLI_COLOR_PREPEND = "\033[";
    const CLI_COLOR_STRDELI = "m";
    const CLI_COLOR_APPEND = "\033[0m";

    const LOG_INFO = "info";
    const LOG_WARN = "warn";
    const LOG_ERROR = "error";

    /** @var int LOGFILE_SIZE Size limit for logfiles.*/
    const LOGFILE_SIZE = 8192;

    /** @var string LOGFILE_PATH Path for logfiles. */
    const LOGFILE_PATH = "/var/log/";

    /**
     * Logs an info as static method.
     *
     * For convenient usage of logging.
     * @param string $message
     * @param string|null $logfilename
     *@see MainLogger::logInfo()
     *
     */
    public static function info($message, $logfilename = null) {
        $instance = new MainLogger($logfilename);
        $instance->logInfo($message);
        unset($instance);
    }

    /**
     * Logs an important info as static method.
     *
     * For convenient usage of logging.
     * @param string $message
     * @param string|null $logfilename
     *@see MainLogger::logInfoImportant()
     *
     */
    public static function infoImportant($message, $logfilename = null) {
        $instance = new MainLogger($logfilename);
        $instance->logInfoImportant($message);
        unset($instance);
    }

    /**
     * Logs a warn message as static method.
     *
     * For convenient usage of logging.
     * @param string $message
     * @param string|null $logfilename
     *@see MainLogger::logWarn()
     *
     */
    public static function warn($message, $logfilename = null) {
        $instance = new MainLogger($logfilename);
        $instance->logWarn($message);
        unset($instance);
    }

    /**
     * Logs an error message as static method.
     *
     * For convenient usage of logging.
     * @param string $message
     * @param string|null $logfilename
     *@see MainLogger::logError()
     *
     */
    public static function error($message, $logfilename = null) {
        $instance = new MainLogger($logfilename);
        $instance->logError($message);
        unset($instance);
    }

    /**
     * MainLogger constructor.
     *
     * Starts the logger and opens a logfile if given.
     *
     * @param string|null $logfilename The name for the logfile.
     */
    public function __construct($logfilename = null) {
        if(!is_null($logfilename)) {
            $this->_logfilename = $logfilename;
            $this->_fd = fopen(MainLogger::LOGFILE_PATH.$this->_logfilename, "a+");
        }
    }

    /**
     * MainLogger destructor.
     *
     * Cleans everything up after ending and closes the logfile if there was one.
     */
    public function __destruct() {
        if(is_resource($this->_fd)) {
            fclose($this->_fd);
            $this->_fd = null;
        }
    }

    /**
     * Logs an info.
     *
     * Simple info logger for CLI and if given a logfile.
     *
     * @param string $message The message to be logged.
     * @param bool $logtofile If true, $message will be written into logfile, otherweise only visible on CLI.
     */
    public function logInfo($message, $logtofile = false) {
        $msg = $this->logMsgPrepend($message, MainLogger::LOG_INFO);

        echo $msg."\n";

        if($logtofile && is_resource($this->_fd)) {
            fwrite($this->_fd, $msg."\n");
        }

        $this->setNewLogfile();
    }

    /**
     * Logs an important info.
     *
     * Simple info logger for CLI and if given a logfile.
     * This one sets color for CLI, for important info messages.
     *
     * @param string $message The message to be logged.
     * @param bool $logtofile If true, $message will be written into logfile, otherweise only visible on CLI.
     */
    public function logInfoImportant($message, $logtofile = false) {
        $msg = $this->logMsgPrepend($message, MainLogger::LOG_INFO);

        echo MainLogger::CLI_COLOR_PREPEND.MainLogger::CLI_BLACK.MainLogger::CLI_COLOR_STRDELI.MainLogger::CLI_COLOR_PREPEND.MainLogger::CLI_BG_GREEN.MainLogger::CLI_COLOR_STRDELI;
        echo $msg;
        echo MainLogger::CLI_COLOR_APPEND;
        echo "\n";

        if($logtofile && is_resource($this->_fd)) {
            fwrite($this->_fd, $msg."\n");
        }

        $this->setNewLogfile();
    }

    /**
     * Logs a warn message.
     *
     * Simple warn logger for CLI and if given a logfile.
     * Method for non critical errors.
     *
     * @param string $message The message to be logged.
     * @param bool $logtofile If true, $message will be written into logfile, otherweise only visible on CLI.
     */
    public function logWarn($message, $logtofile = false) {
        $msg = $this->logMsgPrepend($message, MainLogger::LOG_WARN);

        echo MainLogger::CLI_COLOR_PREPEND.MainLogger::CLI_RED.MainLogger::CLI_COLOR_STRDELI.MainLogger::CLI_COLOR_PREPEND.MainLogger::CLI_BG_YELLOW.MainLogger::CLI_COLOR_STRDELI;
        echo $msg;
        echo MainLogger::CLI_COLOR_APPEND;
        echo "\n";

        if($logtofile && is_resource($this->_fd)) {
            fwrite($this->_fd, $msg."\n");
        }

        $this->setNewLogfile();
    }

    /**
     * Logs an error message.
     *
     * Simple warn logger for CLI and if given a logfile.
     * Method for critical errors and aborts.
     *
     * @param string $message The message to be logged.
     * @param bool $logtofile If true, $message will be written into logfile, otherweise only visible on CLI.
     */
    public function logError($message, $logtofile = false) {
        $msg = $this->logMsgPrepend($message, MainLogger::LOG_ERROR);

        echo MainLogger::CLI_COLOR_PREPEND.MainLogger::CLI_YELLOW.MainLogger::CLI_COLOR_STRDELI.MainLogger::CLI_COLOR_PREPEND.MainLogger::CLI_BG_RED.MainLogger::CLI_COLOR_STRDELI;
        echo $msg;
        echo MainLogger::CLI_COLOR_APPEND;
        echo "\n";

        if($logtofile && is_resource($this->_fd)) {
            fwrite($this->_fd, $msg."\n");
        }

        $this->setNewLogfile();
    }

    /**
     * Prepends info to log message.
     *
     * Sets loglevel, date and host in front of the log message for info.
     *
     * @param string $message to be logged.
     * @param string $loglevel Const log level as for info when reading logs.
     * @return string The message with the info prepended.
     */
    private function logMsgPrepend($message, $loglevel) {
        return date("d-m-Y H:i:s")." - ".StatusList::CLIENTHOST." - ".$loglevel." | ".$message;
    }

    /**
     * Creates new logfile if filesize is too big.
     *
     * Applies only if logfile is given.
     * Checks on every logwrite (on every loglevel) if the filesize is bigger than the configuration limit.
     * If so, closes the logfile, counts already existend logfiles in directory and renames it with the filecound at
     * the end of the filename.
     * Then opens a new logfile for writing.
     */
    private function setNewLogfile() {
        if(!is_resource($this->_fd)) { return; }
        if(filesize(MainLogger::LOGFILE_PATH.$this->_logfilename) < MainLogger::LOGFILE_SIZE) { return; }

        $dir = opendir(MainLogger::LOGFILE_PATH);
        $i = 0;
        while(false !== ($dirfile = readdir($dir))) {
            if(strpos($dirfile, $this->_logfilename) !== false) { $i++; }
        }
        closedir($dir);

        fclose($this->_fd);
        rename(MainLogger::LOGFILE_PATH.$this->_logfilename, MainLogger::LOGFILE_PATH.$this->_logfilename.".".$i);
        $this->_fd = fopen(MainLogger::LOGFILE_PATH.$this->_logfilename, "a+");
    }
}
