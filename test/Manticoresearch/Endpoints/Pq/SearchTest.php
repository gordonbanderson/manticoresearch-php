<?php declare(strict_types = 1);

namespace Manticoresearch\Test\Endpoints\Pq;

use Manticoresearch\Client;
use Manticoresearch\Endpoints\Pq\Search;
use Manticoresearch\Exceptions\RuntimeException;

class SearchTest extends \PHPUnit\Framework\TestCase
{

    public function testMissingIndexName(): void
    {
        $client = new Client();
        $params = [
            'body' => [
                'query' => [
                    'percolate' => [
                        'document' => [
                            'subject'=>'test',
                            'content' => 'some content',
                            'catid' =>5,
                        ],
                    ],
                ],
            ],
        ];
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Index name is missing.');
        $client->pq()->doc($params);
    }

    public function testSetGetIndex(): void
    {
        $search = new Search();
        $search->setIndex('products');
        $this->assertEquals('products', $search->getIndex());
    }

    public function testMethod(): void
    {
        $search = new Search();
        $this->assertEquals('POST', $search->getMethod());
    }

    public function testGetPath(): void
    {
        $search = new Search();
        $search->setIndex('products');
        $this->assertEquals('/json/pq/products/_search', $search->getPath());
    }

    public function testGetPathIndexMissing(): void
    {
        $search = new Search();
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Index name is missing');
        $search->getPath();
    }

}
