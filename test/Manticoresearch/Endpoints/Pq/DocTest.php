<?php declare(strict_types = 1);

namespace Manticoresearch\Test\Endpoints\Pq;

use Manticoresearch\Client;
use Manticoresearch\Endpoints\Pq\Doc;
use Manticoresearch\Exceptions\RuntimeException;

class DocTest extends \PHPUnit\Framework\TestCase
{

    public function testMissingIndexName(): void
    {
        $client = new Client();
        $params = [

            'body' => [
                'query' => ['match'=>['subject'=>'test']],
                'tags' => ['test1','test2'],
            ],
        ];
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Index name is missing.');
        $response = $client->pq()->doc($params);
    }

    public function testSetGetIndex(): void
    {
        $doc = new Doc();
        $doc->setIndex('products');
        $this->assertEquals('products', $doc->getIndex());
    }

    public function testSetGetID(): void
    {
        $doc = new Doc();
        $doc->setId(4);
        $this->assertEquals(4, $doc->getId());
    }

    public function testGetPathNoID(): void
    {
        $doc = new Doc();
        $doc->setIndex('products');
        $this->assertEquals('/json/pq/products/doc', $doc->getPath());
    }

    public function testGetPathWithID(): void
    {
        $doc = new Doc();
        $doc->setIndex('products');
        $doc->setId(4);
        $this->assertEquals('/json/pq/products/doc/4', $doc->getPath());
    }

    public function testMethod(): void
    {
        $dbq = new Doc();
        $this->assertEquals('POST', $dbq->getMethod());
    }

}
