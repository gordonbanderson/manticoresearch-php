<?php declare(strict_types = 1);

namespace Manticoresearch\Test\Endpoints;

use Manticoresearch\Test\Helper\PopulateHelperTest;

class ReloadIndexesTest extends \PHPUnit\Framework\TestCase
{

    public function testReloadIndexes(): void
    {
        $helper = new PopulateHelperTest();
        $client = $helper->getClient();
        $response = $client->nodes()->reloadindexes();
        $this->assertEquals(['total'=>0, 'error'=>'', 'warning'=>''], $response);
    }

}
