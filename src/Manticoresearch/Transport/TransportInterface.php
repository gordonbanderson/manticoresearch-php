<?php declare(strict_types = 1);

namespace Manticoresearch\Transport;

use Manticoresearch\Connection;
use Manticoresearch\Request;
use Manticoresearch\Transport;

/**
 * Interface TransportInterface
 *
 * @package Manticoresearch\Transport
 */
interface TransportInterface
{

    /**
     * @param array $params
     * @return mixed
     */
    public function execute(Request $request, array $params = []);

    /** @return mixed */
    public function getConnection();

    public function setConnection(Connection $connection): Transport;

}
