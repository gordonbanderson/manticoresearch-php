<?php declare(strict_types = 1);

namespace Manticoresearch\Test\Connection\Strategy;

use Manticoresearch\Client;
use Mockery as mock;
use PHPUnit\Framework\TestCase;

class StaticRoundRobinTest extends TestCase
{

    public function testTwoConnections(): void
    {
        $client = new Client(["connectionStrategy" =>"StaticRoundRobin"]);

        $client->setHosts([
            [
                'host' => $_SERVER['MS_HOST'],
                'port' => $_SERVER['MS_PORT'],
                'transport' => isset($_SERVER['TRANSPORT']) ? $_SERVER['TRANSPORT'] : 'Http',
            ],
            [
                'host' => $_SERVER['MS_HOST'],
                'port' => $_SERVER['MS_PORT'],
                'transport' => isset($_SERVER['TRANSPORT']) ? $_SERVER['TRANSPORT'] : 'Http',
            ],
        ]);

        $connection = $client->getConnectionPool()->getConnection();
        $this->assertSame($_SERVER['MS_HOST'], $connection->getHost());
        $this->assertSame($_SERVER['MS_PORT'], $connection->getPort());

        $connection = $client->getConnectionPool()->getConnection();
        $this->assertSame($_SERVER['MS_HOST'], $connection->getHost());
    }

    public function testBadFirst(): void
    {

        $client = new Client(["connectionStrategy" =>"StaticRoundRobin"]);

        $client->setHosts([
            [
                'host' => $_SERVER['MS_HOST'],
                'port' => 9309,
            ],
            [
                'host' => $_SERVER['MS_HOST'],
                'port' => $_SERVER['MS_PORT'],
            ],

        ]);

        $params = [
            'index' => 'testrt',
            'body' => [
                'columns' => [
                    'title' => [
                        'type' => 'text',
                        'options' => ['indexed', 'stored'],
                    ],
                ],
                'silent' => true,
            ],
        ];
        $response = $client->indices()->create($params);
        $this->assertEquals([
            'total' => 0,
            'error' => '',
            'warning' => '',
        ], $response);
        $params = [
            'body' => [
                'index' => 'testrt',
                'query' => [
                    'match_phrase' => [
                        'title' => 'find me',
                    ],
                ],
            ],
        ];

        $client->search($params);
        $this->assertSame($_SERVER['MS_PORT'], $client->getConnectionPool()->getConnection()->getPort());
    }

    public function testSequence(): void
    {

        $mConns = [];

        for ($i=0; $i<10; $i++) {
            $mConns[] = mock::mock(\Manticoresearch\Connection::class)
                ->shouldReceive('isAlive')->andReturn(true)->getMock();
        }

        $connectionPool = new \Manticoresearch\Connection\ConnectionPool(
            $mConns,
            new \Manticoresearch\Connection\Strategy\StaticRoundRobin(),
            10
        );

        for ($i=0; $i<10; $i++) {
            $c = $connectionPool->getConnection();
            $this->assertSame($mConns[0], $c);
        }
    }

}
