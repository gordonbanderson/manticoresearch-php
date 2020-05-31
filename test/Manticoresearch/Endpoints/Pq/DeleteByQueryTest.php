<?php declare(strict_types = 1);

namespace Manticoresearch\Test\Endpoints\Pq;

use Manticoresearch\Endpoints\Pq\DeleteByQuery;
use Manticoresearch\Exceptions\RuntimeException;

class DeleteByQueryTest extends \PHPUnit\Framework\TestCase
{

    public function testSetGetIndex(): void
    {
        $dbq = new DeleteByQuery();
        $dbq->setIndex('products');
        $this->assertEquals('products', $dbq->getIndex());
    }

    public function testMethod(): void
    {
        $dbq = new DeleteByQuery();
        $this->assertEquals('POST', $dbq->getMethod());
    }

    public function testGetPath(): void
    {
        $dbq = new DeleteByQuery();
        $dbq->setIndex('products');
        $this->assertEquals('/json/pq/products/_search', $dbq->getPath());
    }

    public function testGetPathIndexMissing(): void
    {
        $dbq = new DeleteByQuery();
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Index name is missing');
        $dbq->getPath();
    }

}
