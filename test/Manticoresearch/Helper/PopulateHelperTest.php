<?php declare(strict_types = 1);

namespace Manticoresearch\Test\Helper;

use Manticoresearch\Client;

class PopulateHelperTest extends \PHPUnit\Framework\TestCase
{

    /** @var \Manticoresearch\Client */
    private $client;

    public function getClient(): Client
    {
        $params = [
            'host' => $_SERVER['MS_HOST'],
            'port' => $_SERVER['MS_PORT'],
            'transport' => isset($_SERVER['TRANSPORT']) ? $_SERVER['TRANSPORT'] : 'Http',
        ];
        $this->client = new Client($params);

        return $this->client;
    }

    public function populateForKeywords(): void
    {
        $this->getClient();

        $this->client->indices()->drop([
            'index' => 'products',
                'body' => ['silent' => true],
            ]);

        $params = [
            'index' => 'products',
            'body' => [
                'columns' => [
                    'title' => [
                        'type' => 'text',
                        'options' => ['indexed', 'stored'],
                    ],
                    'price' => [
                        'type' => 'float',
                    ],
                ],
                'settings' => [
                    'rt_mem_limit' => '256M',
                    'min_infix_len' => '3',
                ],
                'silent' => true,
            ],
        ];
        $this->client->indices()->create($params);
        $this->client->replace([
            'body'=> [
                'index' => 'products',
                'id'=> 100,
                'doc' => [
                    'title' =>'this product is not broken',
                    'price' => 2.99,
                ],
            ],
        ]);
    }

    /**
     * @param string $indexName the name of the index
     * @param string $query the search query
     * @param int $numberOfResultsExpected how many results are expected
     * @return array|\Manticoresearch\Response
     */
    public function search(string $indexName, string $query, int $numberOfResultsExpected)
    {
        $this->getClient();

        $search = [
            'body' => [
                'index' => $indexName,
                'query' => [
                    'match' => ['*' => $query],
                ],
            ],
        ];
        $results = $this->client->search($search);
        $actualTotal = $results['hits']['total'];
        $this->assertEquals($numberOfResultsExpected, $actualTotal);

        return $results;
    }

    public function describe($indexName)
    {
        return $this->client->indices()->describe(['index'=> $indexName]);
    }

    public function status($indexName)
    {
        return $this->client->nodes()->status();
    }

    public function testDummy(): void
    {
        $a = 1;
        $this->assertEquals(1, $a);
    }

}
