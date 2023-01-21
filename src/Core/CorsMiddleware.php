<?php

namespace PlicniTeplice\Recipes\Api\Core;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CorsMiddleware implements MiddlewareInterface
{
    /**
     * Associative array with domain => [allowed methods] list
     * @var array
     */
    protected $cors = [
//        'https://erecept.plicniteplice.cz' => ['POST'],
//        'https://erecept-admin-e1e99c57.plicniteplice.cz' => ['GET', 'POST', 'PUT']
    '*' => ['GET', 'POST', 'PUT', 'OPTIONS']
    ];

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		$response = $handler->handle($request);
		$origin = $_SERVER['HTTP_ORIGIN'] ?? 'none';
		return $this->getResponse($response, $origin, $this->cors);
	}


    /**
     * Gets allow method string of comma separated http verbs
     * @param string $origin origin domain
     * @param array $cors access list with methods
     * @return string           comma delimited string of methods
     */
    private function getAllowedMethodsString($cors, $origin)
    {
        $methods = $cors[$origin];
        if (is_array($methods)) {
            $methods = implode(', ', $methods);
        }
        return $methods;
    }

    /**
     * Gets the proper origin header value
     * @param array $cors cors config
     * @param string $origin http_origin
     * @return string           origin value
     */
    private function getOriginHeader($cors, $origin)
    {
        if (isset($cors['*'])) {
            return '*';
        }
        return $origin;
    }

    /**
     * Gets appropriate response object
     * @param \Psr\Http\Message\ResponseInterface $response PSR7 Response
     * @param string $origin origin domain
     * @param array $cors access list with methods
     * @return \Psr\Http\Message\ResponseInterface $response PSR7 Response
     */
    private function getResponse($response, $origin, $cors)
    {
        if (isset($cors['*'])) {
            $origin = '*';
        }

        if (!isset($cors[$origin])) {
            return $response;
        }

        return $response
            ->withHeader('Access-Control-Allow-Origin', $this->getOriginHeader($cors, $origin))
            ->withHeader('Access-Control-Allow-Methods', $this->getAllowedMethodsString($cors, $origin))
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization, apikey');
    }
}