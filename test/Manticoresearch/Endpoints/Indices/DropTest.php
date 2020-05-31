<?php declare(strict_types = 1);

namespace Manticoresearch\Test\Endpoints\Indices;

use Manticoresearch\Endpoints\Indices\Drop;
use Manticoresearch\Exceptions\RuntimeException;
use Manticoresearch\Test\Helper\PopulateHelperTest;

class DropTest extends \PHPUnit\Framework\TestCase
{

    /** @var \Manticoresearch\Client */
    private static $client;

    /** @var \Manticoresearch\Test\Helper\PopulateHelperTest */
    private static $helper;

    public function testIndexTruncate(): void
    {
        $response = self::$client->indices()->truncate(['index' => 'products']);

        $this->assertEquals(['total'=>0, 'error'=>'', 'warning'=>''], $response);
    }

    public function testSetGetIndex(): void
    {
        $describe = new Drop();
        $describe->setIndex('testName');
        $this->assertEquals('testName', $describe->getIndex());
    }

    public function testSetBodyNoIndex(): void
    {
        $describe = new Drop();
        $this->expectExceptionMessage('Missing index name in /indices/drop');
        $this->expectException(RuntimeException::class);
        $describe->setBody([]);
    }

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $helper = new PopulateHelperTest();
        $helper->populateForKeywords();
        self::$client = $helper->getClient();
        self::$helper = $helper;
    }

}
