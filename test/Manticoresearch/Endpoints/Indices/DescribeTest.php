<?php declare(strict_types = 1);

namespace Manticoresearch\Test\Endpoints\Indices;

use Manticoresearch\Endpoints\Indices\Describe;
use Manticoresearch\Exceptions\RuntimeException;
use Manticoresearch\Test\Helper\PopulateHelperTest;

class DescribeTest extends \PHPUnit\Framework\TestCase
{

    /** @var \Manticoresearch\Client */
    private static $client;

    /** @var \Manticoresearch\Test\Helper\PopulateHelperTest */
    private static $helper;

    public function testDescribeIndex(): void
    {
        $response = self::$client->indices()->describe(['index' => 'products']);

        $this->assertEquals([
            'id' => [
                'Type' => 'bigint',
                'Properties' => '',
            ],
            'title' => [
                'Type' => 'field',
                'Properties' => 'indexed stored',
            ],
            'price' => [
                'Type' => 'float',
                'Properties' => '',
            ],

        ], $response);
    }

    public function testSetGetIndex(): void
    {
        $describe = new Describe();
        $describe->setIndex('testName');
        $this->assertEquals('testName', $describe->getIndex());
    }

    public function testSetBodyNoIndex(): void
    {
        $describe = new Describe();
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
