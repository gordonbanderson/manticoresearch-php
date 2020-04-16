<?php


namespace Manticoresearch\Query;

use Manticoresearch\Exceptions\RuntimeException;
use Manticoresearch\Query;

class Distance extends Query
{
    public function __construct($args = [])
    {
        $this->_params['geo_distance'] = [];
        $this->_params['geo_distance']['distance_type'] = $args['type'] ?? 'adaptive';
        if (count($args) > 0) {
            if (!isset($args['location_anchor'])) {
                throw new RuntimeException('anchors not provided');
            }
            $this->_params['geo_distance']['location_anchor'] = $args['location_anchor'];
            if (!isset($args['location_source'])) {
                throw new RuntimeException('source attributes not provided');
            }
            if (is_array($args['location_source'])) {
                $args['location_source'] = implode(',', $args['location_source']);
            }
            $this->_params['geo_distance']['location_source'] = $args['location_source'];

            if (!isset($args['location_distance'])) {
                throw new RuntimeException('distance not provided');
            }
            $this->_params['geo_distance']['distance'] = $args['location_distance'];
        }
    }

    public function setDistance($distance)
    {
        $this->_params['geo_distance']['distance'] = $distance;
    }

    public function setSource($source)
    {
        if (is_array($source)) {
            $source = implode(',', $source);
        }
        $this->_params['geo_distance']['location_source'] = $source;
    }

    public function setAnchor($lat, $lon)
    {
        $this->_params['geo_distance']['location_anchor'] = ['lat' => $lat, 'lon' => $lon];
    }

    public function setDistanceType($algorithm)
    {
        $this->_params['geo_distance']['distance_type'] = $algorithm ?? 'adaptive';
    }
}
