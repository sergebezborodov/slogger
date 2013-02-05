<?php

/**
 * Formatter for *nix console with colors
 */
class SLoggerConsoleColorFormatter extends SLoggerDefaultFormater
{
    const COLORFG_BLACK  = '0;30';
    const COLORFG_BLUE   = '0;34';
    const COLORFG_GREEN  = '0;32';
    const COLORFG_CYAN   = '0;36';
    const COLORFG_RED    = '0;31';
    const COLORFG_PURPLE = '0;35';
    const COLORFG_BROWN  = '0;33';
    const COLORFG_YELLOW = '1;33';
    const COLORFG_LGRAY  = '0;37';
    const COLORFG_WHITE  = '1;37';


    const COLORBG_BLACK   = '40';
    const COLORBG_RED     = '41';
    const COLORBG_GREEN   = '42';
    const COLORBG_YELLOW  = '43';
    const COLORBG_BLUE    = '44';
    const COLORBG_MAGENTA = '45';
    const COLORBG_CYAN    = '46';
    const COLORBG_LGRAY   = '47';

    /**
     * @var bool flag for using colors
     */
    public $enable = true;

    /**
     * @var array level => color
     */
    public $colors = array(
        SLogger::TRACE   => self::COLORFG_LGRAY,
        SLogger::LOG     => self::COLORFG_WHITE,
        SLogger::SUCCESS => array(self::COLORFG_BLACK, self::COLORBG_GREEN),
        SLogger::ERROR   => array(self::COLORFG_BLACK, self::COLORBG_RED),
        SLogger::FATAL   => array(self::COLORFG_YELLOW, self::COLORBG_RED),
    );


    /**
     * Add colors for string
     *
     * @param string $string
     * @param string $level
     * @return string
     */
    protected function colorizeLevel($string, $level)
    {
        if (empty($this->colors[$level])) {
            return $string;
        }

        $colored = '';
        $fg = null;
        if (is_array($this->colors[$level])) {
            list($fg, $bg) = $this->colors[$level];
        } else {
            $bg = $this->colors[$level];
        }

        if ($fg) {
            $colored .= "\033[".$fg."m";
        }
        if ($bg) {
            $colored .= "\033[".$bg."m";
        }

        $colored .= $string . "\033[0m";

        return $colored;
    }

    /**
     * Format log message
     *
     * @param mixed $message message to log
     * @param string|null $target target name (file, category, etc)
     * @param string $level log level
     * @param string $from string from __METHOD__ constant
     * @param array|null $data addition data to handler
     * @return string
     */
    public function format($message, $target = null, $level = SLogger::TRACE, $from = null, $data = null)
    {
        $string = parent::format($message, $target, $level, $from, $data);
        if (!$this->enable) {
            return parent::format($string);
        }

        return $this->colorizeLevel($string, $level);
    }
}
