<?php
/**
 * namespace para nosso modulo Base\Email
 */
namespace Base\Email;

use Zend\Mail;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;



/**
 * class EnviarEmail
 * Responsável por Enviar Emails
 *
 * @author Winston Hanun Junior <ceo@sisdeve.com.br> skype ceo_sisdeve
 * @copyright (c) 2015, Winston Hanun Junior
 * @link http://www.sisdeve.com.br
 * @version V0.1
 * @package Base\Email
 */
class EnviarEmail {
    protected $rementente;   // Quem esta Enviando
    protected $destinatario; // Quem vai Receber
    protected $titulo;       // Titulo do Email
    protected $assunto;      // Assunto do Email

    public function __construct($rementente,$destinatario,$titulo,$assunto)
    {
        $this->rementente = $rementente;
        $this->destinatario = $destinatario;
        $this->titulo = $titulo;
        $this->assunto = $assunto;
    }

    public function enviar()
    {
        $body = $this->assunto;
        $htmlPart = new MimePart($body);
        $htmlPart->type = "text/html";

        $textPart = new MimePart($body);
        $textPart->type = "text/plain";

        $body = new MimeMessage();
        $body->setParts(array($textPart, $htmlPart));

        $message = new Mail\Message();
        $message->setFrom($this->rementente);
        $message->addTo($this->destinatario);

        $message->setSubject($this->titulo);
        $message->setEncoding("UTF-8");
        $message->setBody($body);
        $message->getHeaders()->get('content-type')->setType('multipart/alternative');

        $transport = new Mail\Transport\Sendmail();
        $transport->send($message);
    }


}