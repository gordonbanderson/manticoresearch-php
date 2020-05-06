<?php

namespace Manticoresearch\Endpoints\Cluster;

use Manticoresearch\Endpoints\EmulateBySql;
use Manticoresearch\Endpoints\Sql;
use Manticoresearch\Exceptions\RuntimeException;
use Manticoresearch\Utils;

/**
 * @todo maybe pattern should be a query parameter rather than body?
 * Class Status
 * @package Manticoresearch\Endpoints\Indices
 */
class Join extends EmulateBySql
{
    use Utils;
    /**
     * @var string
     */
    protected $cluster;

    public function setBody($params = null)
    {
        error_log('PARAMS');
        error_log(print_r($params, 1));
        if (isset($this->cluster)) {
            error_log('T1');
            if (isset($params['node'])) {
                error_log('T2');

                $this->body = ['query' => "JOIN CLUSTER ".$this->cluster." AT ".$params['node']];
            } else {
                error_log('T3');

                $options =[];
                if (isset($params['path'])) {
                    $options[] = "'".$params['path']. "' AS path";
                }
                if (isset($params['nodes'])) {
                    $options[] = "'".$params['nodes']. "' AS nodes";
                }
                $this->body = ['query' => "JOIN CLUSTER ".$this->cluster.
                    ((count($options)>0)?" ".implode(',', $options):"")];
            }
        } else {
            error_log('T4');
            throw new RuntimeException('Cluster name is missing.');
        }


    }
    /**
     * @return string
     */
    public function getCluster()
    {
        return $this->cluster;
    }

    /**
     * @param mixed $cluster
     */
    public function setCluster($cluster)
    {
        $this->cluster = $cluster;
    }
}
