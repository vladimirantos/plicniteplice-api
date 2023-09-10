<?php


namespace PlicniTeplice\Recipes\Api\Core;

use PHPMailer\PHPMailer\PHPMailer;
use Psr\Log\LoggerInterface;

class EmailSender
{
    private PHPMailer $mailer;
	private LoggerInterface $logger;

    public function __construct(PHPMailer $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
		$this->logger = $logger;
        $this->mailer->isHTML(true);
        $this->mailer->CharSet = 'UTF-8';
    }

    public function from(string $email, string $name = null): EmailSender{
        $this->mailer->setFrom($email, $name);
        return $this;
    }

    public function to(array $address): EmailSender{
        foreach ($address as $email)
            $this->mailer->addAddress($email);
        return $this;
    }

    public function subject(string $subject): EmailSender{
        $this->mailer->Subject = $subject; //html_entity_decode
        return $this;
    }

    public function body(string $body): EmailSender{
        $this->mailer->Body = $body;
        return $this;
    }

    public function altBody(string $body): EmailSender{
        $this->mailer->AltBody = $body;
        return $this;
    }

    public function send(){
        try{
            $this->mailer->send();
            $this->mailer->clearAddresses();
        }catch (\Exception $e){
			$this->logger->error("Chyba odesílání emailu: ".$e->getMessage() . ":::" . $e->getTraceAsString());
        }
    }
}