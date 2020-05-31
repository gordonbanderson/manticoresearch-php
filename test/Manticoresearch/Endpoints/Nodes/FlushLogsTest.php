<?php declare(strict_types = 1);

namespace Manticoresearch\Test\Endpoints;

use Manticoresearch\Test\Helper\PopulateHelperTest;

class FlushLogsTest extends \PHPUnit\Framework\TestCase
{

    public function testFlushLogs(): void
    {
        $helper = new PopulateHelperTest();
        $client = $helper->getClient();
        $response = $client->nodes()->flushlogs();
        $this->assertEquals(['total'=>0, 'error'=>'', 'warning'=>''], $response);
    }

}
