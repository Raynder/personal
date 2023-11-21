<?php

namespace App\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendLinkUploadByCustomerAction extends Mailable
{
    use Queueable, SerializesModels;
    public $mail;
    public $name;
    public $token;

    public function __construct($mail, $name, $token)
    {
        $this->mail = $mail;
        $this->name = $name;
        $this->token = $token;
    }

    public function build(): SendLinkUploadByCustomerAction
    {
        return $this->to($this->mail)
            ->subject('ByToken: Upload de Certificado')
            ->markdown('mail.certificado.link');
    }
}
