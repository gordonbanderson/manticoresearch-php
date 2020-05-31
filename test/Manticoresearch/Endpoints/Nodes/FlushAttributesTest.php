<?php declare(strict_types = 1);

namespace Manticoresearch\Test\Endpoints;

use Manticoresearch\Test\Helper\PopulateHelperTest;

class FlushAttributesTest extends \PHPUnit\Framework\TestCase
{

    public function testFlushAttributes(): void
    {
        $helper = new PopulateHelperTest();
        $client = $helper->getClient();

        $response = $client->nodes()->flushattributes();
        $this->assertEquals('', $response['error']);
    }

}
