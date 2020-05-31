<?php declare(strict_types = 1);

namespace Manticoresearch\Query;

use Manticoresearch\Query;

class QueryString extends Query
{

    public function __construct($string)
    {
        $this->params['query_string'] = $string;
    }

}
