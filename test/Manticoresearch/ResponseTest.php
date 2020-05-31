<?php declare(strict_types = 1);

namespace Manticoresearch\Test;

use Manticoresearch\Exceptions\RuntimeException;
use Manticoresearch\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{

    public function testGetSetTime(): void
    {
        $response = new Response([]);
        $time = \time();
        $response->setTime($time);
        $this->assertEquals($time, $response->getTime());
    }

    public function testGetSetTransportInfo(): void
    {
        $response = new Response([]);
        $transsportInfo = 'transport info';
        $response->setTransportInfo($transsportInfo);
        $this->assertEquals($transsportInfo, $response->getTransportInfo());
    }

    public function testConstructorWithArray(): void
    {
        $payload = ['test' => true];
        $response = new Response($payload);
        $this->assertEquals($payload, $response->getResponse());
    }

    public function testConstructorWithInvalidJSON(): void
    {
        $payload = '["test": this is not valid JSON';
        $response = new Response($payload);

        $this->expectException(RuntimeException::class);
        $response->getResponse();
    }

}
