<?php declare(strict_types = 1);

namespace Manticoresearch\Endpoints;

use Manticoresearch\Request;

/**
 * Class Delete
 *
 * @package Manticoresearch\Endpoints
 */
class Delete extends Request
{

    /** @return string */
    public function getPath()
    {
        return '/json/delete';
    }

    /** @return string */
    public function getMethod()
    {
        return 'POST';
    }

}
