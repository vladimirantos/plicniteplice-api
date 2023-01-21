<?php

namespace PlicniTeplice\Recipes\Api\Actions;

use Exception;
use PlicniTeplice\Recipes\Api\Core\NotFoundException;
use PlicniTeplice\Recipes\Api\Core\Payload;
use PlicniTeplice\Recipes\Api\Core\Settings\ISettings;
use PlicniTeplice\Recipes\Api\Core\Validations\BaseValidator;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Collection;

abstract class Action
{
    private LoggerInterface $logger;
    private Request $request;
    private Response $response;
    private BaseValidator $validator;

    private array $args;

    public function __construct(ContainerInterface $container) {
        $this->logger = $container->get(LoggerInterface::class);
    }

    public function getLogger(): LoggerInterface {
        return $this->logger;
    }

    public function getRequest(): Request {
        return $this->request;
    }

    public function getResponse(): Response {
        return $this->response;
    }

    public function useValidator(BaseValidator $validator): Action{
        $this->validator = $validator;
        return $this;
    }

    public function __invoke(Request $request, Response $response, array $args): Response {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        try{
            $body = $this->getBody();
            if(!empty($this->validator)){
                $result = $this->validator->isValid($this->getBody());
                if(!$result->isValid())
                    return $this->respondWithData($result->getErrors(), 400);
            }
            return $this->action();
        }
        catch (NotFoundException $e){
            return $response->withStatus(404);
        }
        catch (Exception $e){
            return $response->withStatus(400);
        }
    }

    /**
     * @throws HttpBadRequestException
     */
    protected function getArg(string $name){
        if(!isset($this->args[$name]))
            throw new HttpBadRequestException($this->request, "Could not resolve argument `{$name}`.");
        return $this->args[$name];
    }

    protected function respondWithData($data, int $statusCode = 200): Response{
        return $this->respondPayload($statusCode, $data);
    }

    protected function respond(int $statusCode = 200): Response{
        return $this->respondPayload($statusCode);
    }

    private function respondPayload($statusCode, $data = null): Response{
        if(isset($data))
            $this->response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
        return $this->response->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }

    protected function getBody(): object {
        return (object)$this->request->getParsedBody();
    }

    abstract protected function action(): Response;
}