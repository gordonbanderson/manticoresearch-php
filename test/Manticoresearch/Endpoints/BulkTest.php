<?php declare(strict_types = 1);

namespace Manticoresearch\Test\Endpoints;

use Manticoresearch\Client;
use Manticoresearch\Exceptions\ResponseException;

class BulkTest extends \PHPUnit\Framework\TestCase
{

    private static $client;

    public function testBulkInsertError(): void
    {
        static::$client->bulk(['body' => [
            ['insert' => ['index' => 'bulktest', 'id' => 1, 'doc' => ['title' => 'test']]],
            ['insert' => ['index' => 'bulktest', 'id' => 2, 'doc' => ['title' => 'test']]],
            ['insert' => ['index' => 'bulktest', 'id' => 3, 'doc' => ['title' => 'test']]],
        ]]);
        $this->expectException(ResponseException::class);
        static::$client->bulk(['body' => [
            ['insert' => ['index' => 'bulktest', 'id' => 1, 'doc' => ['title' => 'test']]],
            ['insert' => ['index' => 'bulktest', 'id' => 2, 'doc' => ['title' => 'test']]],
            ['insert' => ['index' => 'bulktest', 'id' => 3, 'doc' => ['title' => 'test']]],
        ]]);
    }

    public function testDelete(): void
    {
        $response = static:: $client->bulk(['body' => [
            ['insert' => ['index' => 'bulktest', 'id' => 4, 'doc' => ['title' => 'test']]],
            ['delete' => ['index' => 'bulktest', 'id' => 2]],
            ['delete' => ['index' => 'bulktest', 'id' => 3]],
        ]]);

        $this->assertEquals(3, \count($response['items']));
        $response = static::$client->search(['body' => ['index' => 'bulktest', 'query' => ['match_all' => '']]]);
        $this->assertEquals(2, $response['hits']['total']);
    }

    public function testSetBodyAsString(): void
    {
        $bulk = new \Manticoresearch\Endpoints\Bulk();
        $bulk->setBody('some string');
        $this->assertEquals('some string', $bulk->getBody());
    }

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $params = [
            'host' => $_SERVER['MS_HOST'],
            'port' => $_SERVER['MS_PORT'],
            'transport' => isset($_SERVER['TRANSPORT']) ? $_SERVER['TRANSPORT'] : 'Http',
        ];

        static::$client = new Client($params);
        $params = [
            'index' => 'bulktest',
            'body' => [
                'columns' => [
                    'title' => [
                        'type' => 'text',
                    ],
                ],
                'silent' => true,
            ],
        ];

        static::$client->indices()->create($params);
        static::$client->indices()->truncate(['index' => 'bulktest']);
    }

}
