<?php

namespace App\Actions;

use App\Models\Certificado;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendChaveMailAction extends Mailable
{
    use Queueable, SerializesModels;
    public $certificado;
    public $mail;
    public $acesso;

    public function __construct(Certificado $certificado, $mail, $acesso)
    {
        $this->certificado = $certificado;
        $this->mail = $mail;
        $this->acesso = $acesso;
    }

    public function build(): SendChaveMailAction
    {
        return $this->to($this->mail)
            ->subject('Chave de acesso')
            ->markdown('mail.certificado.send_chave');
    }
}