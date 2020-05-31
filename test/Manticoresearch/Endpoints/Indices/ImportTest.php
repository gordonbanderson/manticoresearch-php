<?php declare(strict_types = 1);

namespace Manticoresearch\Test\Endpoints\Indices;

use Manticoresearch\Endpoints\Indices\Import;

class ImportTest extends \PHPUnit\Framework\TestCase
{

    public function testSetGetIndex(): void
    {
        $describe = new Import();
        $describe->setIndex('testName');
        $this->assertEquals('testName', $describe->getIndex());
    }

}
