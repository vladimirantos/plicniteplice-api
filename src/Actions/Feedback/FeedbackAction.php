<?php

namespace PlicniTeplice\Recipes\Api\Actions\Feedback;

use PlicniTeplice\Recipes\Api\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;

class FeedbackAction extends Action
{

	protected function action(): Response
	{
		return $this->respond(200);
	}
}