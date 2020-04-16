<?php


namespace Manticoresearch;

/**
 * Class Request
 * @package Manticoresearch
 */
class Request
{
    /**
     * @var string
     */
    protected $_path;
    /**
     * @var string
     */
    protected $_method;
    /**
     * @var array|string
     */
    protected $_body;
    /**
     * @var string
     */
    protected $_query;
    /**
     * @var string
     */
    protected $_content_type;

    public function __construct($params=[])
    {
        if (count($params)>0) {
            $this->setBody($params['body'] ?? []);
            $this->setQuery($params['query'] ?? []);
            $this->setContentType($params['content_type'] ?? 'application/json');
        }
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->_path = $path;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->_body;
    }

    /**
     * @param mixed $body
     */

    public function setBody($body = null)
    {
        $this->_body = $body;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->_method;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->_method = $method;
    }

    /**
     * @return mixed
     */
    public function getContentType()
    {
        return $this->_content_type;
    }

    /**
     * @param mixed $content_type
     */
    public function setContentType($content_type)
    {
        $this->_content_type = $content_type;
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->_query;
    }

    /**
     * @param mixed $query
     */
    public function setQuery($query)
    {
        $this->_query = $query;
    }
    /*
     * #return string
     */
    public function toArray()
    {
        return [
            'path' => $this->getPath(),
            'method' => $this->getMethod(),
            'content_type' => $this->getContentType(),
            'query' => $this->getQuery(),
            'body' => $this->getBody()
        ];
    }
}
