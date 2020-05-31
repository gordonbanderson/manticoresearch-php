<?php declare(strict_types = 1);

namespace Manticoresearch\Test;

use Manticoresearch\Connection;
use Manticoresearch\Exceptions\RuntimeException;
use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase
{

    /** @var \Manticoresearch\Connection */
    private $connection;

    public function setUp(): void
    {
        parent::setUp();

        $this->connection = new Connection([]);
    }

    public function testSetHostGetHost(): void
    {
        $this->connection->setHost('example.com');
        $this->assertEquals('example.com', $this->connection->getHost());
    }

    public function testSetPortGetPort(): void
    {
        $this->connection->setPort(19308);
        $this->assertEquals(19308, $this->connection->getPort());
    }

    public function testSetTimeoutGetTimeout(): void
    {
        $this->connection->setTimeout(12);
        $this->assertEquals(12, $this->connection->getTimeout());
    }

    public function testSetConnectTimeoutGetConnectTimeout(): void
    {
        $this->connection->setConnectTimeout(5);
        $this->assertEquals(5, $this->connection->getConnectTimeout());
    }

    public function testSetTransportGetTransport(): void
    {
        $this->connection->setTransport('http');
        $this->assertEquals('http', $this->connection->getTransport());
    }

    public function testSetHeadersGetHeaders(): void
    {
        $headers = [
            'a' => 1,
            'b' => 2,
        ];

        $this->connection->setheaders($headers);
        $this->assertEquals($headers, $this->connection->getHeaders());
    }

    public function testSetConfigGetAllConfig(): void
    {
        $config = [
            'a' => 1,
            'b' => 2,
        ];

        $this->connection->setConfig($config);

        $configReturned = $this->connection->getConfig();
        $keys = \array_keys($configReturned);
        \sort($keys);

        $this->assertEquals([
            'a',
            'b',
            'connect_timeout',
            'curl',
            'headers',
            'host',
            'password',
            'persistent',
            'port',
            'proxy',
            'scheme',
            'timeout',
            'transport',
            'username',

        ], $keys);
    }

    public function testSetConfigGetConfigByKey(): void
    {
        $config = [
            'a' => 1,
            'b' => 2,
        ];

        $this->connection->setConfig($config);
        $this->assertEquals(2, $this->connection->getConfig('b'));
    }

    public function testStaticCreateSelf(): void
    {
        $newConnection = Connection::create($this->connection);
        $this->assertEquals($this->connection, $newConnection);
    }

    public function testStaticCreateEmptyParams(): void
    {
        $newConnection = Connection::create([]);
        $this->assertEquals($this->connection, $newConnection);
    }

    public function testStaticCreateInvalidParams(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('connection must receive array of parameters or self');
        $newConnection = Connection::create('this is invalid');
        $this->assertEquals($this->connection, $newConnection);
    }

}
