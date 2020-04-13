<?php
namespace Manticoresearch\Test\Helper;


use Manticoresearch\Client;
use PHPUnit\Framework\TestCase;

class PopulateHelperTest extends \PHPUnit\Framework\TestCase
{
    /** @var Client */
    private $client;

    public function getClient()
    {
        $params = ['host' => $_SERVER['MS_HOST'], 'port' => 9308];
        $this->client = new Client($params);
        return $this->client;
    }

    public function populateForKeywords() {
        $this->getClient();
        $params = [
            'index' => 'products',
            'body' => [
                'columns' => [
                    'title' => [
                        'type' => 'text',
                        'options' => ['indexed', 'stored']
                    ],
                    'price' => [
                        'type' => 'float'
                    ]
                ],
                'settings' => [
                    'rt_mem_limit' => '256M',
                    'min_infix_len' => '3'
                ],
                'silent' => true
            ]
        ];
        $this->client->indices()->create($params);
        $this->client->replace([
            'body'=> [
                'index' => 'products',
                'id'=> 100,
                'doc' => [
                    'title' =>'this product is not broken',
                    'price' => 2.99
                ]
            ]
        ]);
    }

    public function search($indexName, $query, $numberOfResultsExpected)
    {
        $this->getClient();

        $search = [
            'body' => [
                'index' => $indexName,
                'query' => [
                    'match' => ['*' => $query],
                ],
            ]
        ];
        $results = $this->client->search($search);
        $actualTotal = $results['hits']['total'];
        $this->assertEquals($numberOfResultsExpected, $actualTotal);
    }

    public function describe($indexName)
    {
        return $this->client->indices()->describe(['index'=> $indexName]);
    }

    public function status($indexName)
    {
        return $this->client->nodes()->status();
    }
}
