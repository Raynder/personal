<?php

namespace App\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendChaveMailAction extends Mailable
{
    use Queueable, SerializesModels;
    public $certificado;
    public $mail;
    public $acesso;

    public function __construct($certificado, $mail, $acesso)
    {
        $this->certificado = $certificado;
        $this->mail = $mail;
        $this->acesso = $acesso;
    }

    public function build(): SendChaveMailAction
    {
        if(isset($this->certificado->id)){
            return $this->to($this->mail)
                ->subject('Chave de acesso')
                ->markdown('mail.certificado.send_chave');
        }
        else{
            if(count($this->certificado) == 1){
                $this->certificado = $this->certificado[0];
                return $this->to($this->mail)
                    ->subject('Chave de acesso')
                    ->markdown('mail.certificado.send_chave');
            }
            return $this->to($this->mail)
                ->subject('Chave de acesso')
                ->markdown('mail.certificado.send_chaves');
        }
    }
}