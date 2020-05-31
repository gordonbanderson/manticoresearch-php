<?php declare(strict_types = 1);

namespace Manticoresearch\Query;

use Manticoresearch\Query;

class Match extends Query
{

    public function __construct($keywords, $fields)
    {
        $this->params['match'] =[$fields => $keywords];
    }

}
