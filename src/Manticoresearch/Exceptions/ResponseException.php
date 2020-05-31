<?php declare(strict_types = 1);

namespace Manticoresearch\Exceptions;

use Manticoresearch\Request;
use Manticoresearch\Response;

/**
 * Class ResponseException
 *
 * @package Manticoresearch\Exceptions
 */
class ResponseException extends \RuntimeException implements ExceptionInterface
{

    /** @var \Manticoresearch\Request */
    protected $request;

    /** @var \Manticoresearch\Response */
    protected $response;

    /**
     * ResponseException constructor.
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;

        parent::__construct($response->getError());
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

}
