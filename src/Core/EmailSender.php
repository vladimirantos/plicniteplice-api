<?php


namespace PlicniTeplice\Recipes\Api\Core;


use Jdulovit\Api\Service\LoggerService;
use PHPMailer\PHPMailer\PHPMailer;

class EmailSender
{
    /**
     * @var PHPMailer
     */
    private $mailer;

    /**
     * @var LoggerService
     */
    private $logger;

    public function __construct(PHPMailer $mailer, LoggerService $loggerService)
    {
        $this->mailer = $mailer;
        $this->logger = $loggerService;
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
            $this->logger->add("Chyba odesílání emailu", $e->getTraceAsString(), 'exception');
        }
    }
}