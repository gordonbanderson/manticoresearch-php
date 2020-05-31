<?php declare(strict_types = 1);

namespace Manticoresearch\Test\Endpoints;

use Manticoresearch\Exceptions\ResponseException;
use Manticoresearch\Exceptions\RuntimeException;
use Manticoresearch\Test\Helper\PopulateHelperTest;

class SuggestTest extends \PHPUnit\Framework\TestCase
{

    /** @var \Manticoresearch\Client */
    private static $client;

    public function testSuggest(): void
    {
        $params = [
            'index' => 'products',
            'body' => [
                'query'=>'brokn',
                'options' => [
                    'limit' =>5,
                ],
            ],
        ];
        $response = self::$client->suggest($params);
        $this->assertSame('broken', \array_keys($response)[0]);
    }

    public function testSuggestBadIndex(): void
    {
        $params = [
            'index' => 'productsNOT',
            'body' => [
                'query'=>'brokn',
                'options' => [
                    'limit' =>5,
                ],
            ],
        ];
        $this->expectException(\Manticoresearch\Exceptions\ResponseException::class);
        $this->expectExceptionMessage('no such index productsNOT');
        static::$client->suggest($params);
    }

    public function testResponseExceptionViaSuggest(): void
    {
        $params = [
            'index' => 'productsNOT',
            'body' => [
                'query'=>'brokn',
                'options' => [
                    'limit' =>5,
                ],
            ],
        ];

        try {
            $response = static::$client->suggest($params);
        } catch (ResponseException $ex) {
            $request = $ex->getRequest();
            $this->assertEquals("mode=raw&query=CALL SUGGEST('brokn','productsNOT',5 AS limit)", $request->getBody());

            $response = $ex->getResponse();
            $this->assertEquals('"no such index productsNOT"', $response->getError());
        }
    }

    public function testSuggestGetIndex(): void
    {
        $suggest = new \Manticoresearch\Endpoints\Suggest();
        $suggest->setIndex('products');
        $this->assertEquals('products', $suggest->getIndex());
    }

    public function testSuggestNoIndex(): void
    {
        $suggest = new \Manticoresearch\Endpoints\Suggest();
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Index name is missing');
        $suggest->setBody([]);
    }

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $helper = new PopulateHelperTest();
        $helper->populateForKeywords();
        self::$client = $helper->getClient();
    }

}
