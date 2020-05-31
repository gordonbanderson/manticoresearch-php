<?php declare(strict_types = 1);

namespace Manticoresearch\Test\Endpoints;

use Manticoresearch\Client;

class SearchTest extends \PHPUnit\Framework\TestCase
{

    public function testEmptyBody(): void
    {
        $params = [
            'host' => $_SERVER['MS_HOST'],
            'port' => $_SERVER['MS_PORT'],
            'transport' => empty($_SERVER['TRANSPORT']) ? 'Http' : $_SERVER['TRANSPORT'],
        ];
        $client = new Client($params);
        $this->expectException(\Manticoresearch\Exceptions\ResponseException::class);
        $client->search(['body' => '']);
    }

    public function testNoArrayParams(): void
    {
        $params = [
            'host' => $_SERVER['MS_HOST'],
            'port' => $_SERVER['MS_PORT'],
            'transport' => empty($_SERVER['TRANSPORT']) ? 'Http' : $_SERVER['TRANSPORT'],
        ];
        $client = new Client($params);
        $this->expectException(\TypeError::class);
        $client->search('this is not a json');
    }

    public function testMissingIndex(): void
    {
        $params = [
            'host' => $_SERVER['MS_HOST'],
            'port' => $_SERVER['MS_PORT'],
            'transport' => empty($_SERVER['TRANSPORT']) ? 'Http' : $_SERVER['TRANSPORT'],
        ];
        $client = new Client($params);
        $this->expectException(\Manticoresearch\Exceptions\ResponseException::class);
        $client->search([
            'body' => [

                'query' => [
                    'match_phrase' => [
                        'title' => 'find me',
                    ],
                ],
            ],
        ]);
    }

    public function testPath(): void
    {
        $search = new \Manticoresearch\Endpoints\Search();
        $this->assertEquals('/json/search', $search->getPath());
    }

}
