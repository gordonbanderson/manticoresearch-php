<?php
namespace Manticoresearch\Connection;

use Manticoresearch\Connection;
use Manticoresearch\Connection\Strategy\SelectorInterface;

class ConnectionPool
{
    protected $_connections;
    protected $_strategy;

    public function __construct(array $connections, SelectorInterface $strategy)
    {
        $this->_connections = $connections;
        $this->_strategy = $strategy;
    }

    /**
     * @return array
     */
    public function getConnections(): array
    {
        return $this->_connections;
    }

    /**
     * @param array $connections
     */
    public function setConnections(array $connections)
    {
        $this->_connections = $connections;
    }
    public function getConnection():Connection
    {

        $connection =  $this->_strategy->getConnection($this->_connections);
        return $connection;
    }
    public function hasConnections():bool
    {
        foreach($this->_connections as $connection)
        {
            if($connection->isAlive()) {
                return true;
            }
        }
        return false;
    }

}