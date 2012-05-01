<?php

/**
 * File log handler
 */
class SLoggerFileHandler extends SLoggerBaseHandler
{
    /**
     * @var string default file name
     */
    public $defaultFile = 'blackhole';

    /**
     * @var string log file directory
     */
    protected $_path = 'application.runtime';

    /**
     * @var string defaul log file extension (without dot)
     */
    public $extension = 'log';

    /**
     * @var int max file size (in KB) for rotate
     */
    public $rotateSize = 1024;

    /**
     * @var int default directory access mode
     */
    public $directoryMode = 0777;

    /**
     * @var array opened file instances
     */
    protected $fileResources = array();

    /**
     * Get from internal cache or creates new file resource
     *
     * @param string $file file name
     * @return resource
     */
    protected function getFileResource($file)
    {
        if (isset($this->fileResources[$file])) {
            return $this->fileResources[$file];
        }

        return $this->fileResources[$file] = $this->createFileResource($file);
    }

    /**
     * Create file instance resource
     *
     * @param string $file
     * @return resource
     */
    protected function createFileResource($file)
    {
        if (strpos($file, '.') === false) {
            $file .= '.'.$this->extension;
        }

        // file name has directory
        if ((($dirSlash = strpos($file, '/')) !== false) || strpos($file, '\\') !== false) {
            $dir = $this->getPath() . DIRECTORY_SEPARATOR . strrpos($file, $dirSlash ? '/' : '\\');
            if (!file_exists($dir) && !mkdir($dir, $this->directoryMode, true)) {
                throw new SLoggerException("Unable to create log directory '{$dir}'");
            }
        }

        $file = $this->getPath() . DIRECTORY_SEPARATOR . $file;
        if (($fp = fopen($file, 'a')) === false) {
            throw new SLoggerException("Unable to open log file '{$file}'");
        }

        return $fp;
    }

    /**
     * Set log directory path
     *
     * @param string $path path or alias
     */
    public function setPath($path)
    {
        $this->_path = $path;
    }

    /**
     * @return string log directory path
     */
    public function getPath()
    {
        return Yii::getPathOfAlias($this->_path);
    }

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
        if (!$this->getIsLoggedLevel($level)) {
            return false;
        }
        if ($target == null) {
            $target = $this->defaultFile;
        }

        $message = $this->getFormater()->format($message, $target, $level, $from, $data);
        $file = $this->getFileResource($target);

        @flock($file, LOCK_EX);
        fwrite($file, $message);
        @flock($file, LOCK_UN);

        return true;
    }

    /**
     * Close all opened files
     */
    public function __destruct()
    {
        foreach ($this->fileResources as $instance) {
            @fclose($instance);
        }
    }
}