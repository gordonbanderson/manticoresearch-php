<?php declare(strict_types = 1);

namespace Manticoresearch\Test\Endpoints\Indices;

use Manticoresearch\Endpoints\Indices\Status;
use Manticoresearch\Exceptions\RuntimeException;
use Manticoresearch\Test\Helper\PopulateHelperTest;

class StatusTest extends \PHPUnit\Framework\TestCase
{

    /** @var \Manticoresearch\Client */
    private static $client;

    /** @var \Manticoresearch\Test\Helper\PopulateHelperTest */
    private static $helper;

    public function testIndexStatus(): void
    {
        $response = self::$client->indices()->status(['index' => 'products']);

        $this->assertEquals([
            'index_type',
            'indexed_documents',
            'indexed_bytes',
            'ram_bytes',
            'disk_bytes',
            'ram_chunk',
            'ram_chunks_count',
            'disk_chunks',
            'mem_limit',
            'ram_bytes_retired',
            'tid',
            'tid_saved',
            'query_time_1min',
            'query_time_5min',
            'query_time_15min',
            'query_time_total',
            'found_rows_1min',
            'found_rows_5min',
            'found_rows_15min',
            'found_rows_total',
        ], \array_keys($response));
    }

    public function testSetGetIndex(): void
    {
        $describe = new Status();
        $describe->setIndex('testName');
        $this->assertEquals('testName', $describe->getIndex());
    }

    public function testSetBodyNoIndex(): void
    {
        $describe = new Status();
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
