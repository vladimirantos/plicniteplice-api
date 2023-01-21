<?php

namespace PlicniTeplice\Recipes\Api\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Slim\App;

class ApiKeyMiddleware
{
    private App $app;
    public function __construct(App $app) {
        $this->app = $app;
    }

//    public function __invoke(Request $request, RequestHandler $handler): Response{
//        $apikey = $request->getHeader('apikey');
//        if(empty($apikey) || empty($apikey[0]) || $apikey[0] !== $_ENV['API_KEY']){
//            $response = new Response();
//            $response->getBody()->write('Invalid API key');
//            return $response->withStatus(401);
//        }
//        return $handler->handle($request);
//    }


    public function __invoke($request, $response, $next)
    {
		print_r('BANIK PICO');
        if(empty($request->getHeader('APIKEY')) || $request->getHeader('APIKEY')[0] !== $_ENV['API_KEY'])
            return $response->withStatus(401)->withJson('Invalid api key');
        $response = $next($request, $response);
        return $response;
    }
}