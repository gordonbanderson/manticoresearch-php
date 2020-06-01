<?php declare(strict_types = 1);

namespace Manticoresearch\Endpoints\Nodes;

use Manticoresearch\Endpoints\EmulateBySql;

class Debug extends EmulateBySql
{

    /** @var string */
    protected $index;

    public function setBody($params = null)
    {
        return parent::setBody(['query' => "DEBUG " . ($params['subcommand'] ?? "")]);
    }

}
