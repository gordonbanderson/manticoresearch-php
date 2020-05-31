<?php declare(strict_types = 1);

namespace Manticoresearch\Test\Endpoints;

class ReplaceTest extends \PHPUnit\Framework\TestCase
{

    public function testGetPath(): void
    {
        $replace = new \Manticoresearch\Endpoints\Replace();
        $this->assertEquals('/json/replace', $replace->getPath());
    }

    public function testGetMethod(): void
    {
        $replace = new \Manticoresearch\Endpoints\Replace();
        $this->assertEquals('POST', $replace->getMethod());
    }

}
