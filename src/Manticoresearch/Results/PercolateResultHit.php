<?php declare(strict_types = 1);

namespace Manticoresearch\Results;

use Manticoresearch\ResultHit;

class PercolateResultHit extends ResultHit
{

    public function getDocSlots()
    {
        return $this->data['fields']['_percolator_document_slot'];
    }

    public function getDocsMatched($docs)
    {
        return \array_map(static fn ($v) => $docs[$v - 1], $this->data['fields']['_percolator_document_slot']);
    }

}
