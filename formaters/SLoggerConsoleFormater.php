<?php

/**
 * Format log messages with color for unix console
 */
class SLoggerConsoleFormater extends SLoggerDefaultFormater
{
    /**
     * @var bool colorize output
     */
    public $withColors = true;

    public $dateColor = 'green';
    public $messageColor;
    public $fromColor;
    public $targetColor;
    public $levelColor;

    /**
     * @var array background color for levels
     */
    public $levelsBgColors = array(
        SLogger::TRACE   => 'light_gray',
        SLogger::SUCCESS => 'green',
        SLogger::ERROR   => 'red',
    );

    protected static $foregroundColors = array(
        'black'        => '0;30',
        'dark_gray'    => '1;30',
        'blue'         => '0;34',
        'light_blue'   => '1;34',
        'green'        => '0;32',
        'light_green'  => '1;32',
        'cyan'         => '0;36',
        'light_cyan'   => '1;36',
        'red'          => '0;31',
        'light_red'    => '1;31',
        'purple'       => '0;35',
        'light_purple' => '1;35',
        'brown'        => '0;33',
        'yellow'       => '1;33',
        'light_gray'   => '0;37',
        'white'        => '1;37',
    );
    protected static $backgroundColors = array(
        'black'      => '40',
        'red'        => '41',
        'green'      => '42',
        'yellow'     => '43',
        'blue'       => '44',
        'magenta'    => '45',
        'cyan'       => '46',
        'light_gray' => '47',
    );

    /**
     * Return colored string for console
     *
     * @param $string
     * @param string|null $foregroundColor
     * @param string|null $backgroundColor
     * @return string
     */
    protected function getColoredString($string, $foregroundColor = null, $backgroundColor = null) {
        $coloredString = "";

        if ($foregroundColor) {
            if (!isset(self::$foregroundColors[$foregroundColor])) {
                throw new SLoggerException("Unknown foreground color '{$foregroundColor}'");
            }

            $coloredString .= "\033[" . self::$foregroundColors[$foregroundColor] . "m";
        }

        if ($backgroundColor) {
            if (!isset(self::$backgroundColors[$backgroundColor])) {
                throw new SLoggerException("Unknown background color '{$backgroundColor}'");
            }

            $coloredString .= "\033[" . self::$backgroundColors[$backgroundColor] . "m";
        }

        if ($coloredString) {
            return  $coloredString . $string . "\033[0m";
        }

        return $string;
    }

    /**
     * Format log message
     *
     * @param mixed       $message message to log
     * @param string|null $target  target name (file, category, etc)
     * @param string      $level   log level
     * @param string      $from    string from __METHOD__ constant
     * @param array|null  $data    addition data to handler
     *
     * @return string
     */
    public function format($message, $target = null, $level = SLogger::TRACE, $from = null, $data = null)
    {
        if (!$this->withColors) {
            return parent::format($message, $target, $level, $from, $data);
        }

        $bgColor = null;
        if (isset($this->levelsBgColors[$level])) {
            $bgColor = $this->levelsBgColors[$level];
        }


        $values = array(
            '{date}'         => $this->getColoredString(date($this->dateFormat), $this->dateColor, $bgColor),
            '{message}'      => $this->getColoredString($message, $this->messageColor, $bgColor),
            '{target}'       => $this->getColoredString($target, $this->targetColor, $bgColor),
            '{level}'        => $this->getColoredString($level, $this->levelColor, $bgColor) ,
            '{level-spaces}' => $this->getLevelSpaces($level),
            '{from}'         => $this->getColoredString($from, $this->fromColor, $bgColor),
        );

        $message = str_replace(array_keys($values), array_values($values), $this->messageFormat);

        return $message  . PHP_EOL;
    }
}
