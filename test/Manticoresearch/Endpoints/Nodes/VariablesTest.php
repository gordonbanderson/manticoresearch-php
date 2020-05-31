<?php declare(strict_types = 1);

namespace Manticoresearch\Test\Endpoints;

use Manticoresearch\Test\Helper\PopulateHelperTest;

class VariablesTest extends \PHPUnit\Framework\TestCase
{

    public function testVariables(): void
    {
        $helper = new PopulateHelperTest();
        $client = $helper->getClient();
        $response = $client->nodes()->variables();

        $keys = \array_keys($response);
        \sort($keys);
        $this->assertEquals([
            'autocommit',
            'character_set_client',
            'character_set_connection',
            'collation_connection',
            'grouping_in_utc',
            'last_insert_id',
            'log_level',
            'max_allowed_packet',
            'query_log_format',
        ], $keys);
    }

    public function testVariablesWithPattern(): void
    {
        $helper = new PopulateHelperTest();
        $client = $helper->getClient();
        $response = $client->nodes()->variables(['body' => ['pattern' => 'cha%']]);

        $keys = \array_keys($response);
        \sort($keys);
        $this->assertEquals([
            'character_set_client',
            'character_set_connection',
        ], $keys);
    }

    public function testVariablesWithWhere(): void
    {
        $this->markTestSkipped('Not sure of the functionality here');
        /*
        $helper = new PopulateHelperTest();
        $client = $helper->getClient();
        $response = $client->nodes()->variables(['body' => ['where' => ['variable_name' => 'character_set_client' ]]]);

        $keys = array_keys($response);
        sort($keys);
        $this->assertEquals([
            'character_set_client',
            'character_set_connection',
        ], $keys);
        */
    }

}
