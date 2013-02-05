<?php

/**
 * Base formatter for logger
 *
 * @property SLoggerBaseHandler $handler
 */
abstract class SLoggerBaseFormater extends CComponent
{
    public static $bultInFormater = array(
        'default' => 'SLoggerDefaultFormater',
        'console' => 'SLoggerConsoleColorFormatter',
    );

    /**
     * @var SLoggerBaseHandler
     */
    private $_handler;

    /**
     * Creates instance
     *
     * @param SLoggerBaseHandler $handler
     */
    public function __construct($handler)
    {
        $this->_handler = $handler;
    }

    /**
     * Init formater
     */
    public function init()
    {}

    /**
     * @return SLoggerBaseHandler current handler
     */
    protected function getHandler()
    {
        return $this->_handler;
    }

    /**
     * Format log message
     *
     * @abstract
     * @param mixed $message message to log
     * @param string|null $target target name (file, category, etc)
     * @param string $level log level
     * @param string $from string from __METHOD__ constant
     * @param array|null $data addition data to handler
     * @return string
     */
    abstract public function format($message, $target = null, $level = SLogger::TRACE, $from = null, $data = null);
}
