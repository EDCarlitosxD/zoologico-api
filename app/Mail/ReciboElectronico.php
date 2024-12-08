<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
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
    public $recorridos = [];
    public $total = 0;
    public $fechaactual, $nombre, $email;

    public function __construct($boletos, $total, $fechaactual, $nombre, $email, $recorridos)
    {
        $this->boletos = $boletos;
        $this->total = $total;
        $this->fechaactual = $fechaactual;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->recorridos = $recorridos;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recibo Electrónico',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {

        return new Content(
            view: 'emails.Vistavacia'
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = FacadePdf::loadView('emails.Recibo', [
            'boletos' => $this->boletos,
            'total' => $this->total,
            'fechaactual' => $this->fechaactual,
            'nombre' => $this->nombre,
            'email' => $this->email,
            'recorridos' => $this->recorridos
        ]);

        $path = storage_path('app/descargas/recibo_electronico.pdf');
        $pdf->save($path);

        return [$path];
    }
}
