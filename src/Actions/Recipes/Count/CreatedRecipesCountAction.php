<?php

namespace PlicniTeplice\Recipes\Api\Actions\Recipes\Count;

use PlicniTeplice\Recipes\Api\Core\RecipeStatus;
use PlicniTeplice\Recipes\Api\Services\RecipeService;
use Psr\Container\ContainerInterface;

class CreatedRecipesCountAction extends RecipeCountAction
{
    public function __construct(ContainerInterface $container, RecipeService $recipeService) {
        parent::__construct($container, $recipeService, RecipeStatus::CREATED);
    }
}