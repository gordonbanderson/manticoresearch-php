<?php declare(strict_types = 1);

namespace Manticoresearch\Test\Connection;

use Manticoresearch\Client;
use Manticoresearch\Connection;
use PHPUnit\Framework\TestCase;

class ConnectionPoolTest extends TestCase
{

    /** @var \Manticoresearch\Connection\ConnectionPool */
    private $connectionPool;

    public function setUp(): void
    {
        parent::setUp();

        $this->connectionPool = new Connection\ConnectionPool([], new Connection\Strategy\StaticRoundRobin(), 4);
    }

    public function testSetGetStrategy(): void
    {
        // change the connection pool strategy
        $this->connectionPool->setStrategy(new Connection\Strategy\RoundRobin());
        $this->assertEquals(
            'Manticoresearch\Connection\Strategy\RoundRobin',
            \get_class($this->connectionPool->getStrategy()),
        );
    }

    public function testHasConnection(): void
    {
        $this->assertTrue($this->connectionPool->hasConnections());

        $this->connectionPool = new Connection\ConnectionPool([], new Connection\Strategy\StaticRoundRobin(), -1);
        $this->assertFalse($this->connectionPool->hasConnections());
    }

    public function testSetConnections(): void
    {
        $client = new Client();
        $this->assertCount(1, $client->getConnections());
        $connections = $client->getConnections();
        $this->connectionPool->setConnections($connections);
        $this->assertEquals($connections, $this->connectionPool->getConnections());
    }

    public function testGetConnection(): void
    {
        $client = new Client();
        $this->assertCount(1, $client->getConnections());
        $connections = $client->getConnections();
        $this->connectionPool->setConnections($connections);

        $connection = $this->connectionPool->getConnection();
        $this->assertEquals($connections[0], $connection);
    }

    public function testGetConnectionNotAlive(): void
    {
        $client = new Client();
        $this->assertCount(1, $client->getConnections());
        $connections = $client->getConnections();
        $connection = $connections[0];
        $connection->mark(false);
        $this->connectionPool->setConnections([$connection]);

        $connection = $this->connectionPool->getConnection();
        $this->assertEquals($connections[0], $connection);
    }

}
