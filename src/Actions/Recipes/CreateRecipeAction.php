<?php

namespace PlicniTeplice\Recipes\Api\Actions\Recipes;

use Envms\FluentPDO\Exception;
use Envms\FluentPDO\Query;
use PlicniTeplice\Recipes\Api\Core\Validations\RecipeRequestValidator;
use PlicniTeplice\Recipes\Api\Services\RecipeService;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;

class CreateRecipeAction extends RecipeAction
{
    public function __construct(ContainerInterface $container, RecipeService $recipeService) {
        parent::__construct($container, $recipeService);
        $this->useValidator(new RecipeRequestValidator());
    }

    protected function action(): Response {
        try{
            $this->recipeService->create($this->getBody());
            return $this->respond( 200);
        }catch (Exception $e){
            return $this->respondWithData($e->getMessage(), 400);
        }
    }
}