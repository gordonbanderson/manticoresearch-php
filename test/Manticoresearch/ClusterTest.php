<?php

namespace Manticoresearch\Test;

use Manticoresearch\Client;
use Manticoresearch\Connection;
use Manticoresearch\Exceptions\RuntimeException;
use PHPUnit\Framework\TestCase;

class ClusterTest extends TestCase
{
    /**
     * @var Client
     */
    private static $client;

    const CLUSTER_NAME='test_cluster';

    /**
     * Create a cluster
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        $params = ['host' => $_SERVER['MS_HOST'], 'port' => $_SERVER['MS_PORT']];
        self::$client = new Client($params);

        error_log(print_r($_SERVER, 1));

        $nodes = $_SERVER['MS_HOST'] . ':'  .$_SERVER['MS_CLUSTER_PORT'] . ',' . $_SERVER['MS2_HOST'] . ':' .
            $_SERVER['MS2_CLUSTER_PORT'];

        $params = [
            'cluster' => self::CLUSTER_NAME,
            'body' => [
               // 'path' => '/var/data/click_query/',
                'nodes' => $nodes,
            ]
        ];

        error_log('.... creating cluster ....');
        error_log(print_r($params, 1));
        $response = self::$client->cluster()->create($params);
        error_log('.... created cluster ....');
        error_log('Create:' . print_r($response, 1));
    }

    public function testAlterCluster()
    {
        error_log('Work in progress');
    }


    /**
     * Delete the cluster
     */
    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        $params = [
            'cluster' => self::CLUSTER_NAME,
            'body' => [

            ]
        ];
        $response = self::$client->cluster()->delete($params);
        error_log('Delete:' . print_r($response, 1));

    }

}
