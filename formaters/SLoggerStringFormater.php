<?php

/**
 * Base class for string loggers
 */
class SLoggerDefaultFormatter extends SLoggerBaseFormater
{
    /**
     * @var string format string for date and time (for 'date' function)
     */
    public $dateFormat = 'Y-m-d H:i:s';

    /**
     * @var string log message format
     */
    public $messageFormat = '{date} [{level}{level-spaces}]{from} {message}';

    /**
     * @var int string length of longest log level name
     */
    private $_maxLevelLenght;

    /**
     * @return int length of longest log level name
     */
    protected function getMaxLevelLength()
    {
        if ($this->_maxLevelLenght == null) {
            $levels = array();
            foreach ($this->getHandler()->getLevels() as $level) {
                $levels[] = strlen($level);
            }
            sort($levels);
            $this->_maxLevelLenght = array_pop($levels);
        }
        return $this->_maxLevelLenght;
    }

    /**
     * Return spaces after level string for nice format
     *
     * @param string $level
     * @return string
     */
    protected function getLevelSpaces($level)
    {
        return str_repeat(' ', $this->getMaxLevelLength() - strlen($level));
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
        $values = array(
            '{date}'         => date($this->dateFormat),
            '{message}'      => $message,
            '{target}'       => $target,
            '{level}'        => $level,
            '{level-spaces}' => $this->getLevelSpaces($level),
            '{from}'         => $from,
        );

        return str_replace(array_keys($values), array_values($values), $this->messageFormat);
    }
}