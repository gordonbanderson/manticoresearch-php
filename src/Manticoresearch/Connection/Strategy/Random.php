<?php declare(strict_types = 1);

namespace Manticoresearch\Connection\Strategy;

use Manticoresearch\Connection;

/**
 * Class Random
 *
 * @package Manticoresearch\Connection\Strategy
 */
class Random implements SelectorInterface
{

    /** @param array $connections */
    public function getConnection(array $connections): Connection
    {
        \shuffle($connections);

        return $connections[0];
    }

}
