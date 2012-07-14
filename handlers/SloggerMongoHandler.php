<?php

/**
 * Mongo DB logger handler
 */
class SLoggerMongoHandler extends SLoggerBaseHandler
{
    /**
     * @var string default target name
     */
    public $defaultTarget = 'blackhole';

    /**
     * @var string mongo connection string
     */
    public $server = 'mongodb://localhost:27017';

    /**
     * @var string collection name
     */
    public $collection = 'events';

    /**
     * @var string database name
     */
    public $db = 'events';

    /**
     * @var MongoDb
     */
    protected $_db;

    /**
     * @var Mongo
     */
    protected $_connection;

    /**
     * @var MongoCollection
     */
    protected $_collection;


    /**
     * Write log message
     *
     * @param mixed $message message to log
     * @param string|null $target target name (file, category, etc)
     * @param string $level log level
     * @param string $from string from __METHOD__ constant
     * @param array|null $data addition data to handler
     * @return bool
     */
    public function write($message, $target = null, $level = SLogger::TRACE, $from = null, $data = null)
    {
        if (!$this->getIsLoggedLevel($level)) {
            return false;
        }

        if ($target == null) {
            $target = $this->defaultTarget;
        }

        $item = array(
            'message' => $message,
            'target'  => $target,
            'level'   => $level,
            'from'    => $from,
        );
        if ($data) {
            $item['data'] = $data;
        }

        $this->getCollection()->insert($item);

        return true;
    }

    /**
     * @return MongoDb
     */
    protected function getDb()
    {
        if ($this->_db == null) {
            $mongo = new Mongo($this->server);
            $this->_db = $mongo->selectDB($this->db);
        }
        return $this->_db;
    }

    /**
     * @return MongoCollection
     */
    protected function getCollection()
    {
        if ($this->_collection == null) {
            $this->_collection = $this->getDb()->{$this->collection};
        }
        return $this->_collection;
    }

    /**
     * Закрытие соединения с базой
     */
    public function __destruct()
    {
        if ($this->_connection) {
            $this->_connection->close();
        }
    }
}