<?php

namespace PlicniTeplice\Recipes\Api\Actions\Feedback;

use PlicniTeplice\Recipes\Api\Actions\Action;
use PlicniTeplice\Recipes\Api\Core\EmailSender;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;

class FeedbackAction extends Action
{
	private EmailSender $emailSender;
	public function __construct(ContainerInterface $container, EmailSender $emailSender) {
		parent::__construct($container);
		$this->emailSender = $emailSender;
	}

	protected function action(): Response
	{
		$body = $this->getBody();
		$email = empty($body->email) ? 'Pacient neuvedl kontakt' : $body->email;
		$message = "Dostali jsme od pacienta novou zpětnou vazbu: <br> {$body->message} <br> Kontaktní email: {$email}";
		$this->emailSender->from('noreply@plicniteplice.cz')
			->to(['vladimirantos@seznam.cz'])
			->subject('Nová zpětná vazba od pacienta')
			->body($message)
			->send();
		return $this->respond(200);
	}
}