<?php declare(strict_types = 1);

namespace Manticoresearch\Test\Endpoints;

class SQLTest extends \PHPUnit\Framework\TestCase
{

    public function testPath(): void
    {
        $sql = new \Manticoresearch\Endpoints\Sql();
        $this->assertEquals('/sql', $sql->getPath());
    }

    public function testSetGetMode(): void
    {
        $sql = new \Manticoresearch\Endpoints\Sql();
        $sql->setMode('COOLMODE');
        $this->assertEquals('COOLMODE', $sql->getMode());
    }

}
