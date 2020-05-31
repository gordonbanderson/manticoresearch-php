<?php declare(strict_types = 1);

namespace Manticoresearch\Test\Endpoints;

use Manticoresearch\Endpoints\Keywords;
use Manticoresearch\Exceptions\ResponseException;
use Manticoresearch\Test\Helper\PopulateHelperTest;

class KeywordsTest extends \PHPUnit\Framework\TestCase
{

    /** @var \Manticoresearch\Client */
    private static $client;

    public function testKeywords(): void
    {
        $params = [
            'index' => 'products',
            'body' => [
                'query'=>'product',
                'options' => [
                    'stats' =>1,
                    'fold_lemmas' => 1,
                ],
            ],
        ];
        $response = static::$client->keywords($params);
        $this->assertSame('product', $response['1']['normalized']);
    }

    public function testKeywordsBadIndex(): void
    {
        $params = [
            'index' => 'productsNOT',
            'body' => [
                'query'=>'product',
                'options' => [
                    'stats' =>1,
                    'fold_lemmas' => 1,
                ],
            ],
        ];

        $this->expectException(ResponseException::class);
        $this->expectExceptionMessage('no such index productsNOT');
        $response = static::$client->keywords($params);
    }

    public function testSetGetIndex(): void
    {
        $kw = new Keywords();
        $kw->setIndex('products');
        $this->assertEquals('products', $kw->getIndex());
    }

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $helper = new PopulateHelperTest();
        $helper->populateForKeywords();
        self::$client = $helper->getClient();
    }

}
