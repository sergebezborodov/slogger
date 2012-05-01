<?php

/**
 * SLogger shortcut functions
 */
class L
{
    /**
     * @static
     * @return SLogger
     */
    protected static function getLogger()
    {
        return Yii::app()->getComponent('slog');
    }

    /**
     * Log message
     *
     * @param mixed $message message to log
     * @param string|null $target target name (file, category, etc)
     * @param string $from string from __METHOD__ constant
     * @param array|null $data addition data to handler
     * @return int success processed handlers
     */
    public static function trace($message, $target = null, $from = null, $data = null)
    {
        return self::getLogger()->write($message, $target, SLogger::TRACE, $from, $data);
    }

    /**
     * Log message
     *
     * @param mixed $message message to log
     * @param string|null $target target name (file, category, etc)
     * @param string $from string from __METHOD__ constant
     * @param array|null $data addition data to handler
     * @return int success processed handlers
     */
    public static function log($message, $target = null, $from = null, $data = null)
    {
        return self::getLogger()->write($message, $target, SLogger::LOG, $from, $data);
    }

    /**
     * Log message
     *
     * @param mixed $message message to log
     * @param string|null $target target name (file, category, etc)
     * @param string $from string from __METHOD__ constant
     * @param array|null $data addition data to handler
     * @return int success processed handlers
     */
    public static function error($message, $target = null, $from = null, $data = null)
    {
        return self::getLogger()->write($message, $target, SLogger::ERROR, $from, $data);
    }

    /**
     * Log message
     *
     * @param mixed $message message to log
     * @param string|null $target target name (file, category, etc)
     * @param string $from string from __METHOD__ constant
     * @param array|null $data addition data to handler
     * @return int success processed handlers
     */
    public static function success($message, $target = null, $from = null, $data = null)
    {
        return self::getLogger()->write($message, $target, SLogger::SUCCESS, $from, $data);
    }

    /**
     * Log message
     *
     * @param mixed $message message to log
     * @param string|null $target target name (file, category, etc)
     * @param string $from string from __METHOD__ constant
     * @param array|null $data addition data to handler
     * @return int success processed handlers
     */
    public static function fatal($message, $target = null, $from = null, $data = null)
    {
        return self::getLogger()->write($message, $target, SLogger::FATAL, $from, $data);
    }

    /**
     * Log message with custom status
     *
     * @param mixed $name
     * @param array $args
     * @return int success processed handlers
     */
    public static function __callStatic($name, $args)
    {
        $message = isset($args[0]) ? $args[0] : null;
        $target  = isset($args[1]) ? $args[1] : null;
        $level   = $name;
        $from    = isset($args[2]) ? $args[2] : null;
        $data    = isset($args[3]) ? $args[3] : null;

        return self::getLogger()->write($message, $target, $level, $from, $data);
    }
}
