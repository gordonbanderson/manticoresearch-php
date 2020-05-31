<?php declare(strict_types = 1);

namespace Manticoresearch\Connection\Strategy;

use Manticoresearch\Connection;

/**
 * Interface SelectorInterface
 *
 * @package Manticoresearch\Connection\Strategy
 */
interface SelectorInterface
{

    /** @param array $connections */
    public function getConnection(array $connections): Connection;

}
