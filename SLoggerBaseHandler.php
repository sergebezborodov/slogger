<?php

/**
 * Base handler class
 *
 * @property array|string $levels
 * @property SLoggerBaseFormater $formatter
 * @property SLogger $logger
 */
abstract class SLoggerBaseHandler extends CComponent
{
    public static $bultInHandlers = array(
        'file'    => 'SLoggerFileHandler',
        'console' => 'SLoggerConsoleHandler',
    );

    /**
     * @var string|array log levels
     */
    private $_levelsConfig = '*';

    /**
     * @var array log levels array
     */
    private $_levels;

    /**
     * @var string|array config for default message formater
     */
    protected $_formaterConfig = 'default';

    /**
     * @var SLoggerBaseFormater
     */
    private $_formater;

    /**
     * @var SLogger
     */
    private $_logger;

    /**
     * @param SLogger $logger
     */
    public function __construct($logger)
    {
        $this->_logger = $logger;
    }

    /**
     * Init handler instance
     */
    public function init()
    {}

    /**
     * @return SLogger
     */
    protected function getLogger()
    {
        return $this->_logger;
    }

    /**
     * Set formatter config
     *
     * @param string|array $config
     */
    public function setFormater($config)
    {
        $this->_formaterConfig = $config;
    }

    /**
     * @return SLoggerBaseFormater
     */
    public function getFormater()
    {
        if ($this->_formater == null) {
            $this->_formater = $this->createFormater($this->_formaterConfig);
        }
        return $this->_formater;
    }

    /**
     * Create formater
     *
     * @param string|array $config
     * @return SLoggerBaseFormater
     */
    protected function createFormater($config)
    {
        if (is_string($config)) {
            if (!isset(SLoggerBaseFormater::$bultInFormater[$config])) {
                throw new SLoggerException("Unknown type '{$config}' for formater config");
            }
            $config = array('class' => SLoggerBaseFormater::$bultInFormater[$config]);
        }
        $formater = Yii::createComponent($config, $this);
        $formater->init();
        return $formater;
    }

    /**
     * Set levels for logger
     *
     * @param string|array $levels
     */
    public function setLevels($levels)
    {
        $this->_levelsConfig = $levels;
    }

    /**
     * @return array
     */
    public function getLevels()
    {
        if ($this->_levels == null) {
            $this->_levels = $this->createLevelsArray($this->_levelsConfig);
        }
        return $this->_levels;
    }

    /**
     * Check if handler support level for log
     *
     * @param string $level
     * @return bool
     */
    protected function getIsLoggedLevel($level)
    {
        return in_array($level, $this->getLevels());
    }

    /**
     * Process levels property and return array with levels names
     *
     * @param string|array
     * @return array
     */
    protected function createLevelsArray($value)
    {
        if (is_string($value) && $value == '*') {
            return $this->getLogger()->getLevels();
        }
        if (is_string($value)) {
            return preg_split('/[\s,]+/', $value, -1, PREG_SPLIT_NO_EMPTY);
        }

        if (is_array($value)) {
            return $value;
        }

        throw new SLoggerException("Bad config for levels property");
    }

    /**
     * Write log message
     *
     * @abstract
     * @param mixed $message message to log
     * @param string|null $target target name (file, category, etc)
     * @param string $level log level
     * @param string $from string from __METHOD__ constant
     * @param array|null $data addition data to handler
     * @return bool
     */
    abstract public function write($message, $target = null, $level = SLogger::TRACE, $from = null, $data = null);
}
