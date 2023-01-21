<?php

namespace PlicniTeplice\Recipes\Api\Core;

class CorsMiddleware
{
    /**
     * Associative array with domain => [allowed methods] list
     * @var array
     */
    protected $cors = [
        'https://erecept.plicniteplice.cz' => ['POST'],
        'https://erecept-admin-e1e99c57.plicniteplice.cz' => ['GET', 'POST', 'PUT']
    ];

    /**
     * Middleware invokable class
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request PSR7 request
     * @param \Psr\Http\Message\ResponseInterface $response PSR7 response
     * @param callable $next Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function run($request, $response, $next)
    {
        $response = $next($request, $response);
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : 'none';
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