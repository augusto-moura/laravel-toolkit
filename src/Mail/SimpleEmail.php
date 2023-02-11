<?php

namespace AugustoMoura\LaravelToolkit\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;

/*
Mail::to('example@email.com')->queue(new SimpleEmail(
	'Title', 
	'Body', 
	[$attachment1Path, $attachment2Path]
));
*/

class SimpleEmail extends Mailable implements ShouldQueue
{
    use Queueable;

	public $titulo, $mensagem, $anexos;

	public function __construct(string $titulo, string $mensagem, array $anexos = [])
    {
		$_SERVER['SERVER_NAME'] = config('app.mail_domain') ?? '127.0.0.1';

        $this->titulo = $titulo;
        $this->mensagem = $mensagem;
        $this->anexos = $anexos;
    }

    public function build()
    {
		$mensagem = $this->mensagem;

        $mail = $this->subject($this->titulo)
			->markdown('am-laravel-toolkit::simple-email', compact('mensagem'));

		foreach($this->anexos as $anexo){
			$mail = $mail->attach($anexo);
		}

		return $mail;
    }
}
