<?php
namespace Manticoresearch\Test;

use Manticoresearch\Client;
use Manticoresearch\Connection;
use Manticoresearch\Connection\Strategy\Random;
use Manticoresearch\Exceptions\ConnectionException;
use Manticoresearch\Test\Helper\PopulateHelperTest;
use PHPUnit\Framework\TestCase;

class ClusterTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $helper = new PopulateHelperTest();
        $client = $helper->getClient();
        $params = [
            'cluster' => 'testcluster',
            'body' => [
 //               'path' => '/var/data/click_query/',
                'nodes' => '127.0.0.1:9312,127.0.0.1:19312',

            ]
        ];
        $response = $client->cluster()->create($params);

        error_log('Created cluster testcluster');
        error_log(print_r($response, 1));
    }

    public function testAlterDropWithoutAdd()
    {
        $helper = new PopulateHelperTest();
        $client = $helper->getClient();
        $params = [
            'cluster' => 'testcluster',
            'body' => [
                'operation' => 'drop',
                'index' => 'nonExistentIndex'
            ]
        ];
        $this->expectException(\Manticoresearch\Exceptions\ResponseException::class);
        $this->expectExceptionMessage("unknown index 'nonExistentIndex'");
        $client->cluster()->alter($params);
    }

    public function testAlterAddThenDrop()
    {
        $helper = new PopulateHelperTest();
        $client = $helper->getClient();

        // create an index on one node
        $params = [
            'index' => 'testrt',
            'body' => [
                'columns' => [
                    'title' => [
                        'type' => 'text',
                        'options' => ['indexed', 'stored']
                    ],
                    'gid' => [
                        'type' => 'integer'
                    ]
                ],
                'settings' => [
                    'rt_mem_limit' => '256M',
                    'min_infix_len' => '3'
                ]
            ]
        ];
        $response = $client->indices()->create($params);
        $this->assertEquals(['total' => 0, 'error' => '', 'warning' => ''], $response);

        // add it to the cluster
        $params = [
            'cluster' => 'testcluster',
            'body' => [
                'operation' => 'add',
                'index' => 'testrt'
            ]
        ];
        $response = $client->cluster()->alter($params);
        $this->assertEquals(['total' => 0, 'error' => '', 'warning' => ''], $response);

        // drop the index from the cluster
        $params = [
            'cluster' => 'testcluster',
            'body' => [
                'operation' => 'drop',
                'index' => 'testrt'
            ]
        ];
        $response = $client->cluster()->alter($params);
        $this->assertEquals(['total' => 0, 'error' => '', 'warning' => ''], $response);

        // drop the index from the initial node
        $response = $client->indices()->drop(['index' => 'testrt', 'silent' => true]);
        $this->assertEquals(['total' => 0, 'error' => '', 'warning' => ''], $response);
    }


    public function testSet()
    {
        $helper = new PopulateHelperTest();
        $client = $helper->getClient();
        $params = [
            'cluster' => 'testcluster',
            'body' => [
                'variable'=> [
                    'name' => 'pc.bootstrap',
                    'value'=>'`'
                ]
            ]
        ];
        $response = $client->cluster()->set($params);
        $this->assertEquals(['total' => 0, 'error' => '', 'warning' => ''], $response);
    }

    public function testJoin()
    {
        $helper = new PopulateHelperTest();
        $client = $helper->getClient();
        $params = [
            'cluster' => 'testcluster',
            'body' => [
                'node'
            ]
        ];
        $response = $client->cluster()->join($params);
        $this->assertEquals(['total' => 0, 'error' => '', 'warning' => ''], $response);
    }


    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        $helper = new PopulateHelperTest();
        $client = $helper->getClient();
        $params = [
            'cluster' => 'testcluster',
            'body' => [

            ]
        ];
        $response = $client->cluster()->delete($params);
    }
}