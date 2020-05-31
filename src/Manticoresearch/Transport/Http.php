<?php declare(strict_types = 1);

namespace Manticoresearch\Transport;

use Manticoresearch\Exceptions\ConnectionException;
use Manticoresearch\Exceptions\ResponseException;
use Manticoresearch\Request;
use Manticoresearch\Response;

/**
 * Class Http
 *
 * @package Manticoresearch\Transport
 */
class Http extends \Manticoresearch\Transport implements TransportInterface
{

    /** @var string */
    protected $scheme = 'http';

    /** @var resource */
    protected static $curl;

    /** @param array $params */
    public function execute(Request $request, array $params = []): Response
    {
        $connection = $this->getConnection();
        $conn = $this->getCurlConnection($connection->getConfig('persistent'));
        $url = $this->scheme.'://'.$connection->getHost().':'.$connection->getPort();
        $endpoint = $request->getPath();
        $url .= $endpoint;
        $url = $this->setupURI($url, $request->getQuery());

        \curl_setopt($conn, \CURLOPT_URL, $url);
        \curl_setopt($conn, \CURLOPT_TIMEOUT, $connection->getTimeout());
        \curl_setopt($conn, \CURLOPT_ENCODING, '');
        \curl_setopt($conn, \CURLOPT_FORBID_REUSE, 0);
        $data = $request->getBody();
        $method = $request->getMethod();
        $headers = $connection->getHeaders();
        $headers[] = \sprintf('Content-Type: %s', $request->getContentType());

        if (!empty($data)) {
            $content = \is_array($data)
                ? \json_encode($data, \JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES)
                : $data;

            \curl_setopt($conn, \CURLOPT_POSTFIELDS, $content);
        } else {
            \curl_setopt($conn, \CURLOPT_POSTFIELDS, '');
        }

        \curl_setopt($conn, \CURLOPT_CUSTOMREQUEST, $method);
        \curl_setopt($conn, \CURLOPT_HTTPHEADER, $headers);

        if ($connection->getConnectTimeout()>0) {
            \curl_setopt($conn, \CURLOPT_CONNECTTIMEOUT, $connection->getConnectTimeout());
        }

        if ($connection->getConfig('username') !== null && $connection->getConfig('password') !== null) {
            \curl_setopt($conn, \CURLOPT_HTTPAUTH, \CURLAUTH_ANY);
            \curl_setopt(
                $conn,
                \CURLOPT_USERPWD,
                $connection->getConfig('username').":".$connection->getConfig('password'),
            );
        }

        if ($connection->getConfig('proxy') !== null) {
            \curl_setopt($conn, \CURLOPT_PROXY, $connection->getConfig('proxy'));
        }

        if (!empty($connection->getConfig('curl'))) {
            foreach ($connection->getConfig('curl') as $k => $v) {
                \curl_setopt($conn, $k, $v);
            }
        }

        $start = \microtime(true);
        \ob_start();
        \curl_exec($conn);
        $responseString = \ob_get_clean();
        $end = \microtime(true);
        $errorno = \curl_errno($conn);
        $status = \curl_getinfo($conn, \CURLINFO_HTTP_CODE);

        if (isset($params['responseClass'])) {
            $responseClass = $params['responseClass'];
            $response = new $responseClass($responseString, $status);
        } else {
            $response = new Response($responseString, $status);
        }

        $time = $end-$start;
        $response->setTime($time);
        $response->setTransportInfo([
                'url' => $url,
                'headers' => $headers,
                'body' => $request->getBody(),
            ]);

        //hard error
        if ($errorno>0) {
            $error = \curl_error($conn);

            /* @phpstan-ignore-next-line */
            self::$curl = false;

            throw new ConnectionException($error, $request);
        }

        $this->logger->debug('Request body:', [
                'connection' => $connection->getConfig(),
                'payload'=> $request->getBody(),
            ]);
        $this->logger->info(
            'Request:',
            [
                    'url' => $url,
                    'status' => $status,
                    'time' => $time,
                ],
        );
        $this->logger->debug('Response body:', [\json_decode($responseString, true)]);

        //soft error
        if ($response->hasError()) {
            $this->logger->error('Response error:', [$response->getError()]);

            throw new ResponseException($request, $response);
        }

        return $response;
    }

    /** @return false|resource */
    protected function getCurlConnection(bool $persistent = true)
    {
        if (!$persistent || !self::$curl) {
            self::$curl = \curl_init();
        }

        return self::$curl;
    }

}
