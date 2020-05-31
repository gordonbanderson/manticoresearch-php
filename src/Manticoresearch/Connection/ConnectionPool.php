<?php declare(strict_types = 1);

namespace Manticoresearch\Connection;

use Manticoresearch\Connection;
use Manticoresearch\Connection\Strategy\SelectorInterface;
use Manticoresearch\Exceptions\NoMoreNodesException;

/**
 * Class ConnectionPool
 *
 * @package Manticoresearch\Connection
 */
class ConnectionPool
{

    /** @var \Manticoresearch\Connection\Strategy\SelectorInterface */
    public $strategy;

    public $retries;

    public $retries_attempts =0;

    /** @var array */
    protected $connections;

    public function __construct(array $connections, SelectorInterface $strategy, int $retries)
    {
        $this->connections = $connections;
        $this->strategy = $strategy;
        $this->retries = $retries;
    }

    /** @return array */
    public function getConnections(): array
    {
        return $this->connections;
    }

    /** @param array $connections */
    public function setConnections(array $connections): void
    {
        $this->connections = $connections;
    }

    public function getConnection(): Connection
    {
        $this->retries_attempts++;
        $connection = $this->strategy->getConnection($this->connections);

        if ($connection->isAlive()) {
            return $connection;
        }

        if ($this->retries_attempts < $this->retries) {
            return $connection;
        }

        throw new NoMoreNodesException('No more retries left');
    }

    public function hasConnections(): bool
    {
        return $this->retries_attempts < $this->retries;
    }

    public function getStrategy(): SelectorInterface
    {
        return $this->strategy;
    }

    public function setStrategy(SelectorInterface $strategy): void
    {
        $this->strategy = $strategy;
    }

}
