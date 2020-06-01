<?php declare(strict_types = 1);

namespace Manticoresearch\Endpoints;

use Manticoresearch\Request;

/**
 * Class Insert
 *
 * @package Manticoresearch\Endpoints
 */
class Insert extends Request
{

    /** @return string */
    public function getPath()
    {
        return '/json/insert';
    }

    /** @return string */
    public function getMethod()
    {
        return 'POST';
    }

}
