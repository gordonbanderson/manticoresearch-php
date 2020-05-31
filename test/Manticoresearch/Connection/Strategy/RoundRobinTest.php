<?php declare(strict_types = 1);

namespace Manticoresearch\Test\Connection\Strategy;

use Manticoresearch\Client;
use Mockery as mock;
use PHPUnit\Framework\TestCase;

class RoundRobinTest extends TestCase
{

    public function testSequenceGood(): void
    {
        $client = new Client(["connectionStrategy" =>"RoundRobin"]);

        $client->setHosts([
            [
                'host' => $_SERVER['MS_HOST'],
                'port' => $_SERVER['MS_PORT'],
                'transport' => isset($_SERVER['TRANSPORT']) ? $_SERVER['TRANSPORT'] : 'Http',
            ],
            [
                'host' => $_SERVER['MS_HOST'],
                'port' => 9309,
                'transport' => isset($_SERVER['TRANSPORT']) ? $_SERVER['TRANSPORT'] : 'Http',
            ],
        ]);

        $connection = $client->getConnectionPool()->getConnection();
        $this->assertSame($_SERVER['MS_HOST'], $connection->getHost());
        $this->assertSame($_SERVER['MS_PORT'], $connection->getPort());

        $connection = $client->getConnectionPool()->getConnection();
        $this->assertSame($_SERVER['MS_HOST'], $connection->getHost());
        $this->assertSame(9309, $connection->getPort());

        $mConns = [];

        for ($i=0; $i<10; $i++) {
            $mConns[] = mock::mock(\Manticoresearch\Connection::class)
                ->shouldReceive('isAlive')->andReturn(true)->getMock();
        }

        $connectionPool = new \Manticoresearch\Connection\ConnectionPool(
            $mConns,
            new \Manticoresearch\Connection\Strategy\RoundRobin(),
            10,
        );

        foreach (\range(0, 9) as $i) {
            $c = $connectionPool->getConnection();
            $this->assertSame($mConns[$i], $c);
        }
    }

}
