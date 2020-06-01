<?php declare(strict_types = 1);

namespace Manticoresearch\Endpoints\Indices;

use Manticoresearch\Endpoints\EmulateBySql;
use Manticoresearch\Exceptions\RuntimeException;
use Manticoresearch\Utils;

class FlushRtindex extends EmulateBySql
{

    use Utils;

    /** @var string */
    protected $index;

    public function setBody($params = null)
    {

        if (isset($this->index)) {
            return parent::setBody(['query' => "FLUSH RTINDEX ".$this->index]);
        }

        throw new RuntimeException('Index name is missing.');
    }

    /** @return mixed */
    public function getIndex()
    {
        return $this->index;
    }

    /** @param mixed $index */
    public function setIndex($index): void
    {
        $this->index = $index;
    }

}
