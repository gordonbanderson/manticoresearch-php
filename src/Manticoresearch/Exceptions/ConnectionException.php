<?php declare(strict_types = 1);

namespace Manticoresearch\Exceptions;

use Manticoresearch\Request;

/**
 * Class ConnectionException
 *
 * @package Manticoresearch\Exceptions
 */
class ConnectionException extends \RuntimeException implements ExceptionInterface
{

    /** @var \Manticoresearch\Request */
    protected $request;

    /**
     * ConnectionException constructor.
     */
    public function __construct(string $message = '', ?Request $request = null)
    {
        $this->request = $request;

        parent::__construct($message);
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }

}
