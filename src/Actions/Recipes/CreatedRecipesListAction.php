<?php

namespace PlicniTeplice\Recipes\Api\Actions\Recipes;

use PlicniTeplice\Recipes\Api\Core\RecipeStatus;
use PlicniTeplice\Recipes\Api\Services\RecipeService;
use Psr\Container\ContainerInterface;

class CreatedRecipesListAction extends RecipesListAction
{
    public function __construct(ContainerInterface $container, RecipeService $recipeService) {
        parent::__construct($container, $recipeService, RecipeStatus::CREATED);
    }
}