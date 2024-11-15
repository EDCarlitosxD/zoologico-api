<?php

namespace App\Mail;

use Barryvdh\DomPDF\PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReciboElectronico extends Mailable
{
    use Queueable, SerializesModels;

    public $boletos = [];
    public $total = 0;
    public $fechaactual, $nombre, $email;

    public function __construct($boletos, $total, $fechaactual, $nombre, $email)
    {
        $this->boletos = $boletos;
        $this->total = $total;
        $this->fechaactual = $fechaactual;
        $this->nombre = $nombre;
        $this->email = $email;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recibo Electronico',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        //$pdf = PDF::loadView('emails.Recibo');
        return new Content(
            view: 'emails.Recibo',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
