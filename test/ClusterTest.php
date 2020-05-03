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
    }


    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        $params = [
            'cluster' => 'mycluster',
            'body' => [

            ]
        ];
        $helper = new PopulateHelperTest();
        $client = $helper->getClient();
        $response = $client->cluster()->delete($params);
    }
}
