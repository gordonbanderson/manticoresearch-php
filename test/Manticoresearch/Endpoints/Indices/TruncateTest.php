<?php declare(strict_types = 1);

namespace Manticoresearch\Test\Endpoints\Indices;

use Manticoresearch\Endpoints\Indices\Truncate;
use Manticoresearch\Exceptions\RuntimeException;
use Manticoresearch\Test\Helper\PopulateHelperTest;

class TruncateTest extends \PHPUnit\Framework\TestCase
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
        $describe = new Truncate();
        $describe->setIndex('testName');
        $this->assertEquals('testName', $describe->getIndex());
    }

    public function testSetBodyNoIndex(): void
    {
        $describe = new Truncate();
        $this->expectExceptionMessage('Index name is missing.');
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
