<?php
declare(strict_types=1);

namespace PlicniTeplice\Recipes\Api\Handlers;

use PlicniTeplice\Recipes\Api\Core\Error;
use PlicniTeplice\Recipes\Api\Core\Payload;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;
use Throwable;

class HttpErrorHandler extends SlimErrorHandler
{
    /**
     * @inheritdoc
     */
    protected function respond(): Response {
        $exception = $this->exception;
        $statusCode = 500;
        $error = new Error(Error::SERVER_ERROR,
            'An internal error has occurred while processing your request.');

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();
            $error->setDescription($exception->getMessage());

            if ($exception instanceof HttpNotFoundException) {
                $error->setType(Error::RESOURCE_NOT_FOUND);
            } elseif ($exception instanceof HttpMethodNotAllowedException) {
                $error->setType(Error::NOT_ALLOWED);
            } elseif ($exception instanceof HttpUnauthorizedException) {
                $error->setType(Error::UNAUTHENTICATED);
            } elseif ($exception instanceof HttpForbiddenException) {
                $error->setType(Error::INSUFFICIENT_PRIVILEGES);
            } elseif ($exception instanceof HttpBadRequestException) {
                $error->setType(Error::BAD_REQUEST);
            } elseif ($exception instanceof HttpNotImplementedException) {
                $error->setType(Error::NOT_IMPLEMENTED);
            }
        }

        if (!($exception instanceof HttpException) && $exception instanceof Throwable && $this->displayErrorDetails ) {
            $error->setDescription($exception->getMessage());
        }

        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write(json_encode($error));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
