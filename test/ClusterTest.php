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
        $params = [
            'cluster' => 'testcluster',
            'body' => [
                'operation' => 'add',
                'index' => 'newindex'
            ]
        ];
        $response = $client->cluster()->alter($params);
        $this->assertEquals([], $response);

        $params = [
            'cluster' => 'testcluster',
            'body' => [
                'operation' => 'drop',
                'index' => 'newindex'
            ]
        ];
        $response = $client->cluster()->alter($params);
        $this->assertEquals([], $response);


    }


    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        $helper = new PopulateHelperTest();
        $client = $helper->getClient();
        $params = [
            'cluster' => 'testcluster',
            'mode' => 'raw',
            'body' => [

            ]
        ];
        $response = $client->cluster()->delete($params);
    }
}
