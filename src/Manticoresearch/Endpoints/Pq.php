<?php declare(strict_types = 1);

namespace Manticoresearch\Endpoints;

use Manticoresearch\Client;
use Manticoresearch\Endpoints\Pq\DeleteByQuery;
use Manticoresearch\Endpoints\Pq\Doc;

/**
 * Class Pq
 *
 * @package Manticoresearch\Endpoints
 */
class Pq
{
    /** @var Client  */
    protected $client;

    /**
     * Pq constructor.
     * @param Client $client Manticoresearch PHP client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function doc(array $params)
    {
        $index = $params['index'] ?? null;
        $id = $params['id'] ?? null;

        $body = $params['body'];
        $endpoint = new Doc();
        $endpoint->setIndex($index);
        $endpoint->setId($id);
        $endpoint->setQuery($params['query'] ?? null);
        $endpoint->setBody($body);
        $response = $this->client->request($endpoint);

        return $response->getResponse();
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function search(array $params, $obj = false)
    {
        $index = $params['index'] ?? null;
        $body = $params['body'];
        $endpoint = new \Manticoresearch\Endpoints\Pq\Search();
        $endpoint->setIndex($index);
        $endpoint->setQuery($params['query'] ?? null);
        $endpoint->setBody($body);
        $response = $this->client->request($endpoint);

        return $obj === true
            ? $response
            : $response->getResponse();
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function deleteByQuery(array $params = [])
    {
        $index = $params['index'] ?? null;
        $body = $params['body'];
        $endpoint = new DeleteByQuery();
        $endpoint->setIndex($index);
        $endpoint->setQuery($params['query'] ?? null);
        $endpoint->setBody($body);
        $response = $this->client->request($endpoint);

        return $response->getResponse();
    }

}
