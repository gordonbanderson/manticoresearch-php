<?php declare(strict_types = 1);

namespace Manticoresearch\Endpoints;

use Manticoresearch\Request;

/**
 * Class Sql
 *
 * @package Manticoresearch\Endpoints
 */
class Sql extends Request
{

    /** @var string */
    protected $mode;

    public function getPath()
    {
        return '/sql';
    }

    /** @return string */
    public function getMethod()
    {
        return 'POST';
    }

    /** @return string */
    public function getBody()
    {
        if ($this->mode === 'raw') {
            $return = ['mode=raw'];
            foreach ($this->body as $k => $v) {
                $return[]= $k.'='.$v;
            }

            return \implode('&', $return);
        }

        return \http_build_query($this->body);
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     */
    public function setMode($mode): void
    {
        $this->mode = $mode;
    }

}
