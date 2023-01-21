<?php

// Define app routes

use PlicniTeplice\Recipes\Api\Actions\Admin\LoginAction;
use PlicniTeplice\Recipes\Api\Actions\Recipes\ClosedRecipesListAction;
use PlicniTeplice\Recipes\Api\Actions\Recipes\Count\ClosedRecipesCountAction;
use PlicniTeplice\Recipes\Api\Actions\Recipes\Count\CreatedRecipesCountAction;
use PlicniTeplice\Recipes\Api\Actions\Recipes\Count\TotalRecipesCountAction;
use PlicniTeplice\Recipes\Api\Actions\Recipes\CreatedRecipesListAction;
use PlicniTeplice\Recipes\Api\Actions\Recipes\CreateRecipeAction;
use PlicniTeplice\Recipes\Api\Actions\Recipes\EditRecipeAction;
use PlicniTeplice\Recipes\Api\Middleware\ApiKeyMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Routing\RouteCollectorProxy;


return function (App $app) {
    // Redirect to Swagger documentation
	$app->get('/test', \PlicniTeplice\Recipes\Api\Actions\TestAction::class);

	$app->group('/api', function (RouteCollectorProxy $group) use ($app) {
		$group->group('/public', function (RouteCollectorProxy $group){
			$group->post('/login', LoginAction::class);
			$group->group('/recipes', function (RouteCollectorProxy $group){
				$group->post('', CreateRecipeAction::class);
			});
		});

		$group->group('/recipes', function (RouteCollectorProxy $group){
			$group->get('', CreatedRecipesListAction::class);
			$group->get('/closed', ClosedRecipesListAction::class);
			$group->get('/created/count', CreatedRecipesCountAction::class);
			$group->get('/count', TotalRecipesCountAction::class);
			$group->get('/closed/count', ClosedRecipesCountAction::class);
			$group->put('/{id}', EditRecipeAction::class);
		});
	});
};
