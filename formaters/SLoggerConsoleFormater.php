<?php

/**
 * Format log messages with color for unix console
 */
class SLoggerConsoleFormater extends SLoggerDefaultFormatter
{
    /**
     * @var bool colorize output
     */
    public $withColors = true;

    public $dateColor;
    public $messageColor;
    public $fromColor;
    public $targetColor;
    public $levelColor;
}