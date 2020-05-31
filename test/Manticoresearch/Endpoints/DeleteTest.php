<?php declare(strict_types = 1);

namespace Manticoresearch\Test\Endpoints;

use Manticoresearch\Test\Helper\PopulateHelperTest;

class DeleteTest extends \PHPUnit\Framework\TestCase
{

    /** @var \Manticoresearch\Client */
    private static $client;

    public function testPath(): void
    {
        $insert = new \Manticoresearch\Endpoints\Delete();
        $this->assertEquals('/json/delete', $insert->getPath());
    }

    public function testGetMethod(): void
    {
        $insert = new \Manticoresearch\Endpoints\Delete();
        $this->assertEquals('POST', $insert->getMethod());
    }

    public function testDelete(): void
    {
        $helper = new PopulateHelperTest();
        $helper->search('products', 'broken', 1);
        $doc = [
            'body' => [
                'index' => 'products',
                'id' => 100,
            ],
        ];

        $response = self::$client->delete($doc);
        $helper->search('products', 'broken', 0);
    }

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $helper = new PopulateHelperTest();
        $helper->populateForKeywords();
        self::$client = $helper->getClient();
    }

}
