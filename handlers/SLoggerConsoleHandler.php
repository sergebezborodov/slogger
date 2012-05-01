<?php

/**
 * Output log messages with echo
 */
class SLoggerConsoleHandler extends SLoggerBaseHandler
{
    /**
     * @var string constant name for checking current is console application
     */
    public $constant = 'CONSOLE_APP';

    /**
     * @var string
     */
    public $_formaterConfig = 'console';

    /**
     * Write log message
     *
     * @param mixed       $message message to log
     * @param string|null $target  target name (file, category, etc)
     * @param string      $level   log level
     * @param string      $from    string from __METHOD__ constant
     * @param array|null  $data    addition data to handler
     *
     * @return bool
     */
    public function write($message, $target = null, $level = SLogger::TRACE, $from = null, $data = null)
    {
        if (!defined($this->constant) || !$this->getIsLoggedLevel($level)) {
            return false;
        }

        echo $this->getFormater()->format($message, $target, $level, $from, $data) . "\r\n";
        return true;
    }

}