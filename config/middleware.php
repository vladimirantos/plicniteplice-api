<?php

use App\Middleware\ValidationExceptionMiddleware;
use PlicniTeplice\Recipes\Api\Core\CorsMiddleware;
use PlicniTeplice\Recipes\Api\Middleware\ApiKeyMiddleware;
use Selective\BasePath\BasePathMiddleware;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;

return function (App $app) {
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
	$app->add(new CorsMiddleware());
};
