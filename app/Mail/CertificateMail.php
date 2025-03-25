<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->view('emails.certificate')
            ->with('data', $this->data)
            ->attach($this->data['certificate_path'], [
                'as' => 'certificate.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
