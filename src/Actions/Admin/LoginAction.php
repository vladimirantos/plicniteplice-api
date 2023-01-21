<?php

namespace PlicniTeplice\Recipes\Api\Actions\Admin;

use Firebase\JWT\JWT;
use PlicniTeplice\Recipes\Api\Actions\Action;
use PlicniTeplice\Recipes\Api\Services\AdminService;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;

class LoginAction extends Action
{
    private AdminService $adminService;
    public function __construct(ContainerInterface $container, AdminService $adminService) {
        parent::__construct($container);
        $this->adminService = $adminService;
    }

    protected function action(): Response {
        $body = $this->getBody();
        $user = $this->adminService->getByEmail($body->email);
		//print_r(password_hash('RPNWBVZFAXwfC9AH', PASSWORD_BCRYPT));
        if(!empty($user)){
            if(password_verify($body->password, $user['password'])){
                $user['token'] = JWT::encode(['id' => $user['id'], 'email' => $user['email']], $_ENV['JWT_SECRET_TOKEN']);
                unset($user['password']);
                return $this->respondWithData($user);
            }
        }

        return $this->respond(401);
    }
}